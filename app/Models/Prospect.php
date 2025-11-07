<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prospect extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'company_name',
        'contact_name',
        'email',
        'phone',
        'website',
        'industry',
        'company_size',
        'annual_revenue',
        'city',
        'state',
        'country',
        'source',
        'priority',
        'status',
        'assigned_to',
        'conversion_probability',
        'notes',
        'last_contacted_at',
        'next_follow_up_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'last_contacted_at' => 'datetime',
            'next_follow_up_at' => 'datetime',
            'conversion_probability' => 'integer',
            'annual_revenue' => 'decimal:2',
        ];
    }

    /**
     * Get the user that owns the prospect.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user assigned to this prospect.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the follow-ups for the prospect.
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    /**
     * Get the deal associated with the prospect.
     */
    public function deal(): HasOne
    {
        return $this->hasOne(Deal::class);
    }

    /**
     * Get the client conversion if exists.
     */
    public function client(): HasOne
    {
        return $this->hasOne(Client::class);
    }

    /**
     * Scope a query to only include active prospects.
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['won', 'lost']);
    }

    /**
     * Scope a query to only include high priority prospects.
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
