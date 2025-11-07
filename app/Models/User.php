<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'current_company_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Check if the user is approved
     */
    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    /**
     * Check if the user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the prospects for the user.
     */
    public function prospects(): HasMany
    {
        return $this->hasMany(Prospect::class);
    }

    /**
     * Get the clients for the user.
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Get the follow-ups for the user.
     */
    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class);
    }

    /**
     * Get the pipeline stages for the user.
     */
    public function pipelineStages(): HasMany
    {
        return $this->hasMany(PipelineStage::class);
    }

    /**
     * Get prospects assigned to this user.
     */
    public function assignedProspects(): HasMany
    {
        return $this->hasMany(Prospect::class, 'assigned_to');
    }

    /**
     * Get the companies the user belongs to.
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class)->withPivot('role')->withTimestamps();
    }

    /**
     * Get the user's current company.
     */
    public function currentCompany()
    {
        return $this->belongsTo(Company::class, 'current_company_id');
    }

    /**
     * Switch to a different company.
     */
    public function switchCompany(Company $company)
    {
        if ($this->companies->contains($company)) {
            $this->current_company_id = $company->id;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Check if user belongs to a company.
     */
    public function belongsToCompany(Company $company)
    {
        return $this->companies->contains($company);
    }

    /**
     * Get user's role in a specific company.
     */
    public function roleInCompany(Company $company)
    {
        $pivot = $this->companies()->where('company_id', $company->id)->first()?->pivot;
        return $pivot?->role;
    }
}
