<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * 🔥 FIELD YANG BOLEH DIISI
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * 🔒 FIELD YANG DISEMBUNYIKAN
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 🔄 CAST DATA
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * 🎯 DEFAULT ROLE (PENTING)
     * User baru otomatis jadi customer
     */
    protected $attributes = [
        'role' => 'user',
    ];

    /**
     * 👑 CEK ADMIN
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * 👤 CEK USER
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}