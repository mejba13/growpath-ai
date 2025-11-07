<?php

/**
 * -----------------------------------------------------------------------------
 * GrowPath AI CRM - A modern, feature-rich Customer Relationship Management
 * (CRM) SaaS application built with Laravel 12, designed to help growing
 * businesses manage prospects, clients, and sales pipelines efficiently.
 * -----------------------------------------------------------------------------
 *
 * @author     Engr Mejba Ahmed
 *
 * @role       AI Developer • Software Engineer • Cloud DevOps
 *
 * @website    https://www.mejba.me
 *
 * @poweredBy  Ramlit Limited — https://ramlit.com
 *
 * @version    1.0.0
 *
 * @since      November 7, 2025
 *
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

class Invoice extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'invoice_number',
        'order_id',
        'subscription_id',
        'company_id',
        'user_id',
        'status',
        'issue_date',
        'due_date',
        'paid_date',
        'subtotal',
        'tax',
        'discount',
        'total',
        'currency',
        'billing_details',
        'line_items',
        'notes',
        'pdf_path',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'billing_details' => 'array',
        'line_items' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            if (! $invoice->invoice_number) {
                $invoice->invoice_number = static::generateInvoiceNumber();
            }
        });
    }

    /**
     * Generate a unique invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        do {
            $number = 'INV-'.now()->format('Ymd').'-'.str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (static::where('invoice_number', $number)->exists());

        return $number;
    }

    /**
     * Get the order for this invoice.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the subscription for this invoice.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the user for this invoice.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company this invoice belongs to.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Check if invoice is paid.
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if invoice is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'overdue' ||
               ($this->status === 'sent' && $this->due_date->isPast());
    }

    /**
     * Check if invoice is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(): bool
    {
        return $this->update([
            'status' => 'paid',
            'paid_date' => now(),
        ]);
    }

    /**
     * Mark invoice as sent.
     */
    public function markAsSent(): bool
    {
        return $this->update([
            'status' => 'sent',
        ]);
    }

    /**
     * Scope to get only paid invoices.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope to get only overdue invoices.
     */
    public function scopeOverdue($query)
    {
        return $query->where(function ($q) {
            $q->where('status', 'overdue')
                ->orWhere(function ($q2) {
                    $q2->where('status', 'sent')
                        ->where('due_date', '<', now());
                });
        });
    }

    /**
     * Get formatted total.
     */
    public function getFormattedTotalAttribute(): string
    {
        return '$'.number_format($this->total, 2);
    }
}
