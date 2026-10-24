<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekomendasiCache extends Model
{
    protected $table = 'rekomendasi_cache';

    protected $fillable = [
        'user_id',
        'rekomendasi_ids',
        'expired_at'
    ];

    protected $casts = [
        'rekomendasi_ids' => 'array',
        'expired_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Cek apakah cache masih valid (belum expired)
     */
    public function isValid(): bool
    {
        return $this->expired_at && $this->expired_at->isFuture();
    }

    /**
     * Ambil rekomendasi sebagai collection of StasiunRadio
     */
    public function getRekomendasiRadios()
    {
        if (empty($this->rekomendasi_ids)) {
            return collect();
        }

        return StasiunRadio::whereIn('id', $this->rekomendasi_ids)
            ->where('is_archieved', false)
            ->get();
    }
}