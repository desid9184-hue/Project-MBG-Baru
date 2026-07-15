<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'school',
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

    public function orders()
    {
        return $this->hasMany(Order::class, 'guru_id');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'driver_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isGuru(): bool
    {
        return $this->role === 'guru';
    }

    public function isAsisten(): bool
    {
        return $this->role === 'asisten';
    }

    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'admin'   => 'Administrator',
            'guru'    => 'Guru',
            'asisten' => 'Asisten Lapangan',
            'driver'  => 'Driver',
            default   => 'Unknown',
        };
    }

    public function getRoleBadgeAttribute(): string
    {
        return match($this->role) {
            'admin'   => 'bg-danger',
            'guru'    => 'bg-primary',
            'asisten' => 'bg-success',
            'driver'  => 'bg-warning',
            default   => 'bg-secondary',
        };
    }
}
