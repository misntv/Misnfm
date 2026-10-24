<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewNewsNotification extends Notification
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
            'title' => 'Berita Baru',
            'body' => 'Berita "' . $this->berita->judul_berita . '" telah dipublikasikan.',
            'url' => route('pendengar.baca.show', $this->berita->id),
        ];
    }
}