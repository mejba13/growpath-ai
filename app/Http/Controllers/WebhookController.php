<?php

/**
 * -----------------------------------------------------------------------------
 * GrowPath AI CRM - A modern, feature-rich Customer Relationship Management
 * (CRM) SaaS application built with Laravel 12, designed to help growing
 * businesses manage prospects, clients, and sales pipelines efficiently.
 * -----------------------------------------------------------------------------
 *
 * @package    GrowPath AI CRM
 * @author     Engr Mejba Ahmed
 * @role       AI Developer • Software Engineer • Cloud DevOps
 * @website    https://www.mejba.me
 * @poweredBy  Ramlit Limited — https://ramlit.com
 * @version    1.0.0
 * @since      November 7, 2025
 * @copyright  (c) 2025 Engr Mejba Ahmed
 * @license    Proprietary - All Rights Reserved
 *
 * Description:
 * GrowPath AI CRM is a comprehensive SaaS platform for customer relationship
 * management, featuring multi-tenancy, role-based access control, subscription
 * management with Stripe & PayPal integration, and advanced CRM capabilities
 * including prospect tracking, client management, and sales pipeline automation.
 *
 * Powered by Ramlit Limited.
 * -----------------------------------------------------------------------------
 */

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    /**
     * Handle Stripe webhook events.
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook.secret');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid webhook payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            Log::error('Invalid webhook signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        try {
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $this->handlePaymentIntentSucceeded($event->data->object);
                    break;

                case 'payment_intent.payment_failed':
                    $this->handlePaymentIntentFailed($event->data->object);
                    break;

                case 'customer.subscription.created':
                case 'customer.subscription.updated':
                    $this->handleSubscriptionUpdated($event->data->object);
                    break;

                case 'customer.subscription.deleted':
                    $this->handleSubscriptionDeleted($event->data->object);
                    break;

                case 'invoice.payment_succeeded':
                    $this->handleInvoicePaymentSucceeded($event->data->object);
                    break;

                case 'invoice.payment_failed':
                    $this->handleInvoicePaymentFailed($event->data->object);
                    break;

                default:
                    Log::info('Unhandled webhook event: ' . $event->type);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook handler error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook handler error'], 500);
        }
    }

    /**
     * Handle successful payment intent.
     */
    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();

        if ($order) {
            $order->markAsPaid();

            // Update payment record
            $payment = Payment::where('gateway_payment_intent', $paymentIntent->id)->first();
            if ($payment) {
                $payment->markAsSucceeded();
            }

            // Update invoice
            if ($order->invoice) {
                $order->invoice->markAsPaid();
            }

            Log::info('Payment succeeded for order: ' . $order->order_number);
        }
    }

    /**
     * Handle failed payment intent.
     */
    protected function handlePaymentIntentFailed($paymentIntent)
    {
        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();

        if ($order) {
            $order->markAsFailed();

            // Update payment record
            $payment = Payment::where('gateway_payment_intent', $paymentIntent->id)->first();
            if ($payment) {
                $payment->markAsFailed($paymentIntent->last_payment_error?->message);
            }

            Log::warning('Payment failed for order: ' . $order->order_number);
        }
    }

    /**
     * Handle subscription update.
     */
    protected function handleSubscriptionUpdated($stripeSubscription)
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            $currentPeriodStart = \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_start);
            $currentPeriodEnd = \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end);
            $trialEnd = $stripeSubscription->trial_end ? \Carbon\Carbon::createFromTimestamp($stripeSubscription->trial_end) : null;

            // Map Stripe status to local status
            $status = match($stripeSubscription->status) {
                'trialing' => 'trial',
                'active' => 'active',
                'past_due' => 'past_due',
                'canceled' => 'cancelled',
                'unpaid' => 'expired',
                default => 'active',
            };

            $subscription->update([
                'status' => $status,
                'current_period_start' => $currentPeriodStart,
                'current_period_end' => $currentPeriodEnd,
                'trial_ends_at' => $trialEnd,
                'next_payment_at' => $currentPeriodEnd,
            ]);

            Log::info('Subscription updated: ' . $subscription->id);
        }
    }

    /**
     * Handle subscription deletion.
     */
    protected function handleSubscriptionDeleted($stripeSubscription)
    {
        $subscription = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if ($subscription) {
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'ends_at' => now(),
            ]);

            Log::info('Subscription cancelled: ' . $subscription->id);
        }
    }

    /**
     * Handle successful invoice payment.
     */
    protected function handleInvoicePaymentSucceeded($invoice)
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();

        if ($subscription) {
            $subscription->update([
                'last_payment_at' => now(),
                'failed_payment_count' => 0,
            ]);

            Log::info('Invoice payment succeeded for subscription: ' . $subscription->id);
        }
    }

    /**
     * Handle failed invoice payment.
     */
    protected function handleInvoicePaymentFailed($invoice)
    {
        $subscription = Subscription::where('stripe_subscription_id', $invoice->subscription)->first();

        if ($subscription) {
            $subscription->increment('failed_payment_count');
            $subscription->update(['status' => 'past_due']);

            Log::warning('Invoice payment failed for subscription: ' . $subscription->id);

            // TODO: Send notification email to user about failed payment
        }
    }
}
