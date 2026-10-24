<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewsReactivationNotification extends Notification
{
    use Queueable;

    protected $berita;

    public function __construct($berita)
    {
        $this->berita = $berita;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Berita Diaktifkan Kembali',
            'body' => 'Berita "' . $this->berita->judul_berita . '" telah diaktifkan kembali dan menunggu verifikasi.',
            'url' => route('admin.berita.verifikasi'),
            'icon' => 'fas fa-redo',
        ];
    }

}