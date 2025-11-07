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

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'company_id',
        'user_id',
        'plan_id',
        'stripe_subscription_id',
        'stripe_customer_id',
        'status',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
        'ends_at',
        'cancelled_at',
        'amount',
        'currency',
        'billing_interval',
        'auto_renew',
        'last_payment_at',
        'next_payment_at',
        'failed_payment_count',
    ];

    protected $casts = [
        'trial_ends_at' => 'date',
        'current_period_start' => 'date',
        'current_period_end' => 'date',
        'ends_at' => 'date',
        'cancelled_at' => 'date',
        'amount' => 'decimal:2',
        'auto_renew' => 'boolean',
        'last_payment_at' => 'datetime',
        'next_payment_at' => 'datetime',
        'failed_payment_count' => 'integer',
    ];

    /**
     * Get the plan for this subscription.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get the user who owns this subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company this subscription belongs to.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get all orders for this subscription.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get all payments for this subscription.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all invoices for this subscription.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Check if subscription is on trial.
     */
    public function onTrial(): bool
    {
        return $this->status === 'trial' && $this->trial_ends_at?->isFuture();
    }

    /**
     * Check if subscription is active.
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['trial', 'active']);
    }

    /**
     * Check if subscription is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if subscription is past due.
     */
    public function isPastDue(): bool
    {
        return $this->status === 'past_due';
    }

    /**
     * Cancel the subscription.
     */
    public function cancel(): bool
    {
        return $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'ends_at' => $this->current_period_end,
            'auto_renew' => false,
        ]);
    }

    /**
     * Resume a cancelled subscription.
     */
    public function resume(): bool
    {
        return $this->update([
            'status' => 'active',
            'cancelled_at' => null,
            'ends_at' => null,
            'auto_renew' => true,
        ]);
    }

    /**
     * Scope to get only active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['trial', 'active']);
    }

    /**
     * Scope to get only trial subscriptions.
     */
    public function scopeOnTrial($query)
    {
        return $query->where('status', 'trial')
            ->where('trial_ends_at', '>', now());
    }

    /**
     * Scope to get expiring subscriptions.
     */
    public function scopeExpiring($query, int $days = 7)
    {
        return $query->where('status', 'active')
            ->whereBetween('current_period_end', [now(), now()->addDays($days)]);
    }
}
