<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_STAFF = 'staff';
    const ROLE_MANAGER = 'manager';
    const ROLE_HR = 'hr';
    const ROLE_USER = 'user';
    const ROLE_APPROVER = 'approver';

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
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'created_by');
    }

    public function approvalTracks(): HasMany
    {
        return $this->hasMany(ApprovalTrack::class, 'approver_id');
    }

    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    public function isManager(): bool
    {
        return $this->role === self::ROLE_MANAGER;
    }

    public function isHR(): bool
    {
        return $this->role === self::ROLE_HR;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function isApprover(): bool
    {
        return $this->role === self::ROLE_APPROVER;
    }
}
