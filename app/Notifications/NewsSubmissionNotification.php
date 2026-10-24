<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewsSubmissionNotification extends Notification
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
            'title' => 'Pengajuan Berita Baru',
            'body' => 'Berita "' . $this->berita->judul_berita . '" telah diajukan oleh wartawan.',
            'url' => route('admin.berita.verifikasi'),
            'icon' => 'fas fa-newspaper',
        ];
    }
}