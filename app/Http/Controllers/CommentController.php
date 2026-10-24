<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\StasiunRadio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentTagged;

class CommentController extends Controller
{
    public function index($radioId)
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->where('commentable_id', $radioId)
            ->where('commentable_type', StasiunRadio::class)
            ->whereNull('parent_id')
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments,
            'total_comments' => Comment::countForRadio($radioId),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'radio_id' => 'required|exists:stasiun_radio,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $radio = StasiunRadio::findOrFail($request->radio_id);

        $comment = new Comment([
            'content' => $request->content,
            'parsed_content' => $this->parseContent($request->content),
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
        ]);

        $radio->comments()->save($comment);

        $notifiedUserIds = [];

        // 🔔 Notifikasi untuk komentar balasan (REPLIED)
        if ($comment->parent_id) {
            $parent = Comment::with('user')->find($comment->parent_id);
            if (
                $parent &&
                $parent->user &&
                $parent->user->id !== Auth::id()
            ) {
                $parent->user->notify(new CommentTagged($comment, 'replied'));
                $notifiedUserIds[] = $parent->user->id;
            }
        }

        // 🔔 Notifikasi untuk user yang di-tag, kecuali yang sudah dikirimi notifikasi
        preg_match_all('/@([\w\-]+)/', $request->content, $matches);
        $usernames = $matches[1] ?? [];

        if (!empty($usernames)) {
            $taggedUsers = User::whereIn('username', $usernames)->get();

            foreach ($taggedUsers as $user) {
                if (
                    $user->id !== Auth::id() &&
                    !in_array($user->id, $notifiedUserIds)
                ) {
                    $user->notify(new CommentTagged($comment, 'tagged'));
                }
            }
        }

        return redirect()->back()->with([
            'success' => 'Komentar berhasil ditambahkan',
            'scroll_to' => 'comments-section',
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        if (!$comment->isEditable()) {
            return redirect()->back()->with([
                'error' => 'Waktu untuk mengedit komentar telah habis (30 menit)',
                'scroll_to' => 'comment-' . $comment->id,
            ]);
        }

        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $comment->update([
            'content' => $request->content,
            'parsed_content' => $this->parseContent($request->content),
        ]);

        return redirect()->back()->with([
            'success' => 'Komentar berhasil diperbarui',
            'scroll_to' => 'comment-' . $comment->id,
        ]);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return redirect()->back()->with([
            'success' => 'Komentar berhasil dihapus',
            'scroll_to' => 'comments-section',
        ]);
    }

    private function parseContent($content)
    {
        $escaped = e($content); // Escape HTML entities

        $parsed = preg_replace_callback('/@([\w\-]+)/', function ($matches) {
            $user = User::where('username', $matches[1])->first();
            if ($user) {
                return '<a href="#" class="text-primary fw-bold mention-user" data-user-id="' . $user->id . '">@' . $user->username . '</a>';
            }
            return $matches[0];
        }, $escaped);

        return nl2br($parsed);
    }
}
