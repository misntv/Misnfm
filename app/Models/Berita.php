<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'berita';

    protected $fillable = [
        'judul_berita',
        'penulis_id',
        'url_gambar',
        'tanggal_kirim',
        'jam_kirim',
        'tanggal_terbit',
        'jam_terbit',
        'topik',
        'isi_berita',
        'status_unggah',
        'is_archieved',
        'edited_since_archieved'
    ];

    protected $casts = [
        'tanggal_kirim' => 'date',
        'jam_kirim' => 'datetime:H:i:s',
        'tanggal_terbit' => 'date',
        'jam_terbit' => 'datetime:H:i:s',
        'is_archieved' => 'boolean',
        'edited_since_archieved' => 'boolean',
    ];


    public function penulis()
    {
        return $this->belongsTo(User::class, 'penulis_id');
    }

    // Scope untuk berita yang sudah diterima
    public function scopeDiterima($query)
    {
        return $query->where('status_unggah', 'diterima');
    }
// app/Models/Berita.php

public function previous()
{
    return static::where('id', '<', $this->id)
                ->where('status_unggah', 'diterima')
                ->orderBy('id', 'desc')
                ->first();
}

public function next()
{
    return static::where('id', '>', $this->id)
                ->where('status_unggah', 'diterima')
                ->orderBy('id', 'asc')
                ->first();
}
    
}