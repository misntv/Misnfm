<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\StasiunRadio;

class CommentTagged extends Notification
{
    use Queueable;

    protected $comment;
    protected $type; // 'tagged' atau 'replied'

    public function __construct($comment, $type = 'tagged')
    {
        $this->comment = $comment;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $comment = $this->comment;
        $type = $this->type;
        $sender = $comment->user->nama_lengkap ?? 'Seseorang';

        // Default nilai URL ke halaman radio
        $url = url('/');
        if ($comment->commentable_type === StasiunRadio::class) {
            $radioId = $comment->commentable_id;
            $url = route('pendengar.player.index', ['id' => $radioId]) . '#comment-' . $comment->id;
        }

        // Menentukan title sesuai role & jenis
        if ($type === 'tagged') {
            $title = "Anda di-tag dalam komentar oleh $sender";
        } else {
            $title = "Komentar Anda dibalas oleh $sender";
        }

        return [
            'title' => $title,
            'body' => strip_tags($comment->content),
            'url' => $url,
            'icon' => 'fas fa-comments'
        ];
    }
}