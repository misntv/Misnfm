<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user_misnfm';

    protected $fillable = [
        'nama_lengkap',
        'username',
        'email',
        'no_identitas',
        'asal_perusahaan',
        'url_foto_kartu_identitas',
        'url_foto_profil',
        'alamat',
        'password',
        'no_telp',
        'role',
        'provider',
        'provider_id',
        'comment',
        'is_active',
        'email_verified_at',
        'approved_at',
        'remember_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin_radio';
    }

    public function isWartawan()
    {
        return $this->role === 'wartawan';
    }

    public function isPendengar()
    {
        return $this->role === 'pendengar';
    }

    public function scopeWartawanUnapproved($query)
    {
        return $query->where('role', 'wartawan')
                    ->whereNull('approved_at');
    }

    public function isApproved()
    {
        return $this->approved_at !== null;
    }
    
    public function riwayatPemutaran()
    {
    return $this->hasMany(RiwayatPemutaran::class, 'user_id');
    }
}