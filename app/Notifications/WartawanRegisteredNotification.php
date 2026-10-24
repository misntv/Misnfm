<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class WartawanRegisteredNotification extends Notification
{
    use Queueable;

    protected $wartawan;

    public function __construct(User $wartawan)
    {
        $this->wartawan = $wartawan;
    }

    public function via($notifiable)
    {
        return ['database']; // Bisa tambah 'mail' jika ingin email juga
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Pendaftaran Wartawan Baru',
            'body' => 'Akun wartawan atas nama ' . $this->wartawan->nama_lengkap . ' telah mendaftar dan menunggu verifikasi.',
            'url' => route('admin.verifikasi.wartawan'),
            'icon' => 'fas fa-user-check',
        ];
    }
}