<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUp extends Model
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
        'type',
        'subject',
        'description',
        'due_date',
        'due_time',
        'priority',
        'status',
        'completed_at',
        'outcome_notes',
        'reminder_sent',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
            'reminder_sent' => 'boolean',
        ];
    }

    /**
     * Get the prospect that owns the follow-up.
     */
    public function prospect(): BelongsTo
    {
        return $this->belongsTo(Prospect::class);
    }

    /**
     * Get the user that owns the follow-up.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include pending follow-ups.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include overdue follow-ups.
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
            ->where('due_date', '<', now());
    }

    /**
     * Scope a query to filter by due date.
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }
}
