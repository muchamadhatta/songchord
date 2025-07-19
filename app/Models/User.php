<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // penting untuk Sanctum

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang bisa diisi massal.
     */
    protected $fillable = [
        'name',
        'username',
        'role',
        'email',
        'password',
        'avatar',
        'last_login_at',
        'is_active',
    ];

    /**
     * Kolom yang disembunyikan dari output JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting kolom ke tipe data tertentu.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'password' => 'hashed', // Laravel 10+
        ];
    }

    /**
     * Optional: cek apakah user aktif.
     */
    public function isActive(): bool
    {
        return $this->is_active === 1;
    }
}
