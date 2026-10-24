<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Models\StasiunRadio;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content',
        'parsed_content',
        'user_id',
        'parent_id',
        'commentable_id',
        'commentable_type',
        'editable_until',
        'edited_at'
    ];

    protected $casts = [
        'editable_until' => 'datetime',
        'edited_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'is_owner',
        'is_deleted'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($comment) {
            $comment->editable_until = Carbon::now()->addMinutes(30);
        });

        static::updating(function ($comment) {
            if ($comment->isDirty('content')) {
                $comment->edited_at = Carbon::now();

                // Reset editable window if needed
                if ($comment->editable_until && $comment->editable_until->isPast()) {
                    $comment->editable_until = Carbon::now()->addMinutes(30);
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->withTrashed();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id')->withTrashed();
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function isEditable(): bool
    {
        if (!$this->editable_until) {
            return false;
        }

        return Carbon::now()->lt($this->editable_until);
    }

  // App\Models\Comment
public function scopeCountForRadio($query, $radioId)
{
    return $query->where('commentable_id', $radioId)
        ->where('commentable_type', StasiunRadio::class)
        ->whereNull('deleted_at') // Hanya komentar yang tidak dihapus
        ->count(); // Ini akan menghitung semua komentar termasuk balasan
}

    public function getIsOwnerAttribute(): bool
    {
        return auth()->check() && $this->user_id === auth()->id();
    }

    public function getIsDeletedAttribute(): bool
    {
        return $this->trashed();
    }

    public function getEditableTimeLeft(): ?string
    {
        if (!$this->editable_until) {
            return null;
        }

        $now = Carbon::now();
        if ($now->gte($this->editable_until)) {
            return null;
        }

        return $this->editable_until->diffForHumans($now, [
            'syntax' => Carbon::DIFF_ABSOLUTE,
            'parts' => 2,
            'join' => ', ',
        ]);
    }

    public function getParsedContentAttribute()
{
    return preg_replace_callback('/@(\w+)/', function ($matches) {
        $username = $matches[1];
        return "<a href=\"/user/{$username}\" class=\"mention-link\">@<span class=\"mention-username\">{$username}</span></a>";
    }, e($this->content));
}
}