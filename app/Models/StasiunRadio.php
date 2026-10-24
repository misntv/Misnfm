<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StasiunRadio extends Model
{
    use HasFactory;

    protected $table = 'stasiun_radio';

    protected $fillable = [
        'nama_stasiun',
        'nama_daerah',
        'url_stasiun',
        'feed_url',
        'jadwal_url',   
        'thumbnail',
        'tanggal_upload',
        'jam_upload',
        'deskripsi',
        'genre',
        'jumlah_pendengar',
        'is_archieved',
    ];

    protected $casts = [
        'tanggal_upload' => 'date',
        'jam_upload' => 'datetime',
        'is_archieved' => 'boolean',
    ];

    // ✅ Accessor untuk URL thumbnail
    public function getThumbnailUrlAttribute()
    {
        return asset('storage/thumbnails/' . $this->thumbnail);
    }

    public function listenerHistory()
    {
        return $this->hasMany(RadioListenerStats::class);
    }



    // Tambahkan relasi komentar
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->morphMany(Comment::class, 'commentable');

    }
    public function listenerStats(): HasMany
    {
        return $this->hasMany(RadioListenerStats::class, 'radio_id');
    }
    public function riwayatPemutaran()
    {
    return $this->hasMany(RiwayatPemutaran::class, 'stasiun_radio_id');
    }

}
