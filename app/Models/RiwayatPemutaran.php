<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatPemutaran extends Model
{
    protected $table = 'riwayat_pemutaran';

    protected $fillable = [
        'user_id',
        'stasiun_radio_id',
        'played_at',
        'durasi',
        'genre_saat_diputar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function radio()
    {
        return $this->belongsTo(StasiunRadio::class, 'stasiun_radio_id');
    }
}