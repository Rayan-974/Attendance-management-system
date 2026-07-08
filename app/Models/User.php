<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'whatsapp_number',
        'password',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function tasksAssignedToMe(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function tasksCreatedByMe(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }
    
    // RBAC Helpers
    public function isAdmin(): bool { return $this->hasRole('admin'); }
    public function isHR(): bool { return $this->hasRole('hr'); }
    public function isTeacher(): bool { return $this->hasRole('teacher'); }
    public function isStudent(): bool { return $this->hasRole('student'); }

    public function getProfilePhotoUrlAttribute(): string
    {
        return $this->profile_photo_path
                    ? asset('storage/' . $this->profile_photo_path)
                    : $this->defaultProfilePhotoUrl();
    }

    protected function defaultProfilePhotoUrl(): string
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\QueuedPasswordReset($token));
    }
}
