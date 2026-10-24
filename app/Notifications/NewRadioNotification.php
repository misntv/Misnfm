<?php

namespace App\Notifications;

use App\Models\StasiunRadio;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewRadioNotification extends Notification
{
    use Queueable;

    protected $radio;

    public function __construct(StasiunRadio $radio)
    {
        $this->radio = $radio;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Stasiun Baru Hadir!',
            'body'  => 'Nikmati siaran seru dari stasiun terbaru: "' . $this->radio->nama_stasiun . '". Yuk, cek sekarang!',
            'url'   => route('pendengar.player.index', $this->radio->id),
        ];
    }
}