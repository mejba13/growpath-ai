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

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Subscription as StripeSubscription;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create or retrieve a Stripe customer.
     */
    public function createOrGetCustomer($user): Customer
    {
        try {
            // Check if user already has a Stripe customer ID
            $subscription = Subscription::where('user_id', $user->id)
                ->whereNotNull('stripe_customer_id')
                ->first();

            if ($subscription && $subscription->stripe_customer_id) {
                return Customer::retrieve($subscription->stripe_customer_id);
            }

            // Create new customer
            return Customer::create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => [
                    'user_id' => $user->id,
                    'company_id' => $user->currentCompany?->id,
                ],
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create Stripe customer: ' . $e->getMessage());
        }
    }

    /**
     * Create a payment intent for one-time payment.
     */
    public function createPaymentIntent(Plan $plan, $user): PaymentIntent
    {
        try {
            $customer = $this->createOrGetCustomer($user);

            return PaymentIntent::create([
                'amount' => $plan->price * 100, // Convert to cents
                'currency' => 'usd',
                'customer' => $customer->id,
                'description' => "Subscription to {$plan->name} plan",
                'metadata' => [
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'company_id' => $user->currentCompany?->id,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create payment intent: ' . $e->getMessage());
        }
    }

    /**
     * Create a Stripe subscription.
     */
    public function createSubscription(Plan $plan, $user, $paymentMethodId): StripeSubscription
    {
        try {
            $customer = $this->createOrGetCustomer($user);

            // Attach payment method to customer
            \Stripe\PaymentMethod::retrieve($paymentMethodId)->attach([
                'customer' => $customer->id,
            ]);

            // Set as default payment method
            Customer::update($customer->id, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethodId,
                ],
            ]);

            // Create subscription
            $stripeSubscription = StripeSubscription::create([
                'customer' => $customer->id,
                'items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $plan->name,
                        ],
                        'recurring' => [
                            'interval' => $plan->billing_interval === 'yearly' ? 'year' : 'month',
                        ],
                        'unit_amount' => ($plan->billing_interval === 'yearly' ? $plan->yearly_price : $plan->price) * 100,
                    ],
                ]],
                'trial_period_days' => $plan->trial_days,
                'metadata' => [
                    'plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'company_id' => $user->currentCompany?->id,
                ],
            ]);

            return $stripeSubscription;
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to create subscription: ' . $e->getMessage());
        }
    }

    /**
     * Cancel a Stripe subscription.
     */
    public function cancelSubscription(Subscription $subscription): void
    {
        try {
            if ($subscription->stripe_subscription_id) {
                StripeSubscription::retrieve($subscription->stripe_subscription_id)->cancel();
            }
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    /**
     * Resume a cancelled Stripe subscription.
     */
    public function resumeSubscription(Subscription $subscription): StripeSubscription
    {
        try {
            return StripeSubscription::update(
                $subscription->stripe_subscription_id,
                ['cancel_at_period_end' => false]
            );
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to resume subscription: ' . $e->getMessage());
        }
    }

    /**
     * Retrieve payment intent.
     */
    public function retrievePaymentIntent(string $paymentIntentId): PaymentIntent
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            throw new \Exception('Failed to retrieve payment intent: ' . $e->getMessage());
        }
    }

    /**
     * Create local subscription record from Stripe subscription.
     */
    public function createLocalSubscription(StripeSubscription $stripeSubscription, Plan $plan, $user): Subscription
    {
        $currentPeriodStart = \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_start);
        $currentPeriodEnd = \Carbon\Carbon::createFromTimestamp($stripeSubscription->current_period_end);
        $trialEnd = $stripeSubscription->trial_end ? \Carbon\Carbon::createFromTimestamp($stripeSubscription->trial_end) : null;

        return Subscription::create([
            'company_id' => $user->currentCompany?->id,
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'stripe_subscription_id' => $stripeSubscription->id,
            'stripe_customer_id' => $stripeSubscription->customer,
            'status' => $stripeSubscription->status === 'trialing' ? 'trial' : 'active',
            'trial_ends_at' => $trialEnd,
            'current_period_start' => $currentPeriodStart,
            'current_period_end' => $currentPeriodEnd,
            'amount' => $plan->billing_interval === 'yearly' ? $plan->yearly_price : $plan->price,
            'currency' => 'usd',
            'billing_interval' => $plan->billing_interval,
            'auto_renew' => true,
            'next_payment_at' => $currentPeriodEnd,
        ]);
    }

    /**
     * Create local order record.
     */
    public function createLocalOrder(PaymentIntent $paymentIntent, Plan $plan, $user, Subscription $subscription = null): Order
    {
        return Order::create([
            'company_id' => $user->currentCompany?->id,
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'subscription_id' => $subscription?->id,
            'type' => $subscription ? 'subscription' : 'one_time',
            'status' => $paymentIntent->status === 'succeeded' ? 'completed' : 'pending',
            'subtotal' => $paymentIntent->amount / 100,
            'tax' => 0,
            'discount' => 0,
            'total' => $paymentIntent->amount / 100,
            'currency' => $paymentIntent->currency,
            'payment_method' => 'stripe',
            'payment_intent_id' => $paymentIntent->id,
            'billing_details' => $paymentIntent->charges->data[0]->billing_details ?? null,
            'paid_at' => $paymentIntent->status === 'succeeded' ? now() : null,
        ]);
    }

    /**
     * Create local payment record.
     */
    public function createLocalPayment(PaymentIntent $paymentIntent, Order $order, Subscription $subscription = null): Payment
    {
        $charge = $paymentIntent->charges->data[0] ?? null;

        return Payment::create([
            'order_id' => $order->id,
            'subscription_id' => $subscription?->id,
            'company_id' => $order->company_id,
            'user_id' => $order->user_id,
            'payment_gateway' => 'stripe',
            'gateway_transaction_id' => $charge?->id,
            'gateway_payment_intent' => $paymentIntent->id,
            'status' => $paymentIntent->status === 'succeeded' ? 'succeeded' : 'pending',
            'amount' => $paymentIntent->amount / 100,
            'currency' => $paymentIntent->currency,
            'payment_method_type' => $charge?->payment_method_details->type,
            'card_last4' => $charge?->payment_method_details->card->last4,
            'card_brand' => $charge?->payment_method_details->card->brand,
            'gateway_response' => $paymentIntent->toArray(),
            'processed_at' => $paymentIntent->status === 'succeeded' ? now() : null,
        ]);
    }
}
