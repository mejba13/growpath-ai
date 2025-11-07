<?php

namespace App\Models;

use App\Traits\BelongsToTenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'prospect_id',
        'user_id',
        'company_name',
        'industry',
        'company_size',
        'contract_start_date',
        'contract_end_date',
        'contract_value',
        'payment_status',
        'account_health_score',
        'renewal_date',
        'services_purchased',
        'notes',
        'converted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'contract_start_date' => 'date',
            'contract_end_date' => 'date',
            'renewal_date' => 'date',
            'contract_value' => 'decimal:2',
            'account_health_score' => 'integer',
            'services_purchased' => 'array',
            'converted_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the client.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the prospect that was converted to this client.
     */
    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    /**
     * Scope a query to only include active clients.
     */
    public function scopeActive($query)
    {
        return $query->where('payment_status', 'current');
    }

    /**
     * Scope a query to filter by payment status.
     */
    public function scopePaymentStatus($query, string $status)
    {
        return $query->where('payment_status', $status);
    }
}
