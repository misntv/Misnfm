<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Berita;

class BeritaStatusNotification extends Notification
{
    use Queueable;

    protected $berita;
    protected $status; // 'diterima' atau 'ditolak'

    public function __construct(Berita $berita, $status)
    {
        $this->berita = $berita;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $judul = strip_tags($this->berita->judul_berita);

        return [
            'title' => "Status Berita Anda Telah " . ucfirst($this->status),
            'body' => "Berita berjudul \"$judul\" yang Anda ajukan telah " . strtoupper($this->status) . " oleh admin.",
            'url' => route('berita.show', $this->berita->id), // pastikan route ini ada
            'icon' => $this->status === 'diterima'
                ? 'fas fa-check-circle text-success'
                : 'fas fa-times-circle text-danger',
        ];
    }
}