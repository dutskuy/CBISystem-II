<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // ← TAMBAHKAN INI

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles; // ← TAMBAHKAN HasRoles di sini

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'company_name',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminOrAbove(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function canSeeProfit(): bool
    {
        return in_array($this->role, ['super_admin', 'owner']);
    }

    public function canManageAdmins(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdminGudang(): bool
    {
        return $this->role === 'admin_gudang';
    }

    public function canManageStock(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'admin_gudang']);
    }

    
}