<div class="comment-container @if($comment->is_deleted) text-muted @endif"
    data-editable-until="{{ $comment->editable_until }}">

    <div class="d-flex align-items-start gap-2 flex-nowrap">
        <img src="{{ $comment->user->url_foto_profil ? asset('storage/' . $comment->user->url_foto_profil) : asset('img/default-user.png') }}"
            class="rounded-circle me-2" width="35" height="35" alt="{{ $comment->user->nama_lengkap }}"
            style="border: 2px solid rgba(255,255,255,0.5); object-fit: cover; flex-shrink: 0;">

        <div class="flex-grow-1 comment-body-container">
            <div class="d-flex justify-content-between align-items-start">
                <div class="d-flex align-items-baseline flex-wrap">
                    <h6 class="mb-0 fw-bold me-1" style="font-size: 0.95rem; line-height: 1.2;">
                        {{ $comment->user->nama_lengkap }}
                    </h6>

                    @if(isset($depth) && $depth > 0 && $comment->parent_id)
                        @php
                            $parentComment = $comment->parent;
                        @endphp
                        @if($parentComment)
                            <small class="text-muted" style="font-size: 0.75rem; line-height: 1.2;">
                                membalas <a href="#" class="mention-link">@<span
                                        class="mention-username">{{ $parentComment->user->username }}</span></a>
                            </small>
                        @endif
                    @endif

                    <small class="text-muted ms-1" style="font-size: 0.75rem; line-height: 1.2;">
                        {{ $comment->created_at->diffForHumans() }}
                        @if($comment->edited_at)
                            • <span class="text-muted">diedit</span>
                        @endif
                    </small>
                </div>

                @if(auth()->check() && $comment->is_owner && !$comment->is_deleted)
                    <div class="dropdown ms-auto">
                        <button class="btn btn-sm btn-link text-muted p-0" type="button"
                            id="commentDropdown{{ $comment->id }}" data-bs-toggle="dropdown" aria-expanded="false"
                            style="font-size: 0.75rem; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-ellipsis-v" style="font-size: 0.8rem;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm py-0"
                            aria-labelledby="commentDropdown{{ $comment->id }}">
                            @if($comment->editable_until)
                                @php
                                    $editableUntil = $comment->editable_until instanceof \Carbon\Carbon
                                        ? $comment->editable_until
                                        : \Carbon\Carbon::parse($comment->editable_until);
                                @endphp
                                @if(now()->lt($editableUntil))
                                    <li>
                                        <button class="dropdown-item py-1 px-2 edit-comment"
                                            style="font-size: 0.75rem; background-color: #964734; color: white !important; border-radius: 4px; margin: 2px;"
                                            data-comment-id="{{ $comment->id }}" data-content="{{ $comment->content }}">
                                            <i class="fas fa-edit me-1" style="font-size: 0.7rem;"></i> Edit
                                        </button>
                                    </li>
                                @endif
                            @endif
                            <li>
                                <form class="delete-comment-form" action="{{ route('comments.destroy', $comment->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item py-1 px-2"
                                        style="font-size: 0.75rem; background-color: #ff4444; color: white !important; border-radius: 4px; margin: 2px;">
                                        <i class="fas fa-trash me-1" style="font-size: 0.7rem;"></i> Hapus
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>

            @if($comment->is_deleted)
                <p class="deleted-comment-msg"><i>Komentar telah dihapus</i></p>
            @else
                <div class="comment-content" style="margin-top: 2px">
                    @php
                        $displayContent = $comment->parsed_content;
                        // Hapus semua mention link jika ini adalah reply
                        if (isset($depth) && $depth > 0) {
                            $displayContent = preg_replace('/<a[^>]*class=["\']mention-link["\'][^>]*>.*?<\/a>/i', '', $displayContent);
                            $displayContent = trim($displayContent);

                            // Juga hapus teks mention yang mungkin ada di awal komentar
                            $displayContent = preg_replace('/^@\w+\s*/', '', $displayContent);
                            $displayContent = trim($displayContent);
                        }
                    @endphp
                    {!! strip_tags($displayContent, '<p><b><i><u><strong><em><ul><ol><li><br><span>') !!}
                </div>
            @endif

            @if(!$comment->is_deleted)
                <div class="d-flex align-items-center mt-1">
                    <button class="btn btn-sm btn-dark reply-btn me-2 rounded-pill text-white"
                        data-comment-id="{{ $comment->id }}" data-username="{{ $comment->user->username }}"
                        style="padding: 0.15rem 0.5rem; font-size: 0.7rem; line-height: 1; border: none;">
                        Balas
                    </button>

                    @if($comment->replies->count() > 0)
                        <button class="btn btn-sm btn-link text-muted toggle-replies p-0" data-comment-id="{{ $comment->id }}"
                            style="font-size: 0.8rem; line-height: 1.2;">
                            <i class="fas fa-chevron-down icon me-1"></i>
                            <span class="reply-count me-1">{{ $comment->replies->count() }}</span>balasan
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Replies --}}
    @if($comment->replies->count() > 0)
        <div class="replies-container" id="replies-{{ $comment->id }}">
            @foreach($comment->replies as $reply)
                @include('pendengar.player.partials.comments', ['comment' => $reply, 'depth' => ($depth ?? 0) + 1])
            @endforeach
        </div>
    @endif
</div>

{{-- CSS --}}
<style>
    .replies-container {
        display: none;
        transition: all 0.3s ease;
        margin-top: 1rem;
    }

    .comment-container {
        background-color: rgba(255, 255, 255, 0.2);
        margin-bottom: 16px;
        border-radius: 8px;
        padding: 12px;
        transition: all 0.2s ease;
    }

    .replies-container>.comment-container {
        margin-left: 0;
        padding-left: 0;
        border-left: none;
        background-color: rgba(255, 255, 255, 0.1);
        margin-bottom: 8px;
    }


    .comment-content,
    .comment-content * {
        font-size: 0.9rem;
        line-height: 1.4;
        text-align: left;
        word-wrap: break-word;
        white-space: normal;
    }

    .mention-link {
        color: #964734;
        font-weight: 500;
        text-decoration: none;
    }

    .mention-link:hover {
        text-decoration: underline;
    }

    .deleted-comment-msg {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 2px;
    }
</style>

{{-- JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleButtons = document.querySelectorAll('.toggle-replies');

        toggleButtons.forEach(button => {
            button.addEventListener('click', function () {
                const commentId = this.getAttribute('data-comment-id');
                const repliesContainer = document.getElementById(replies - ${ commentId });
                const icon = this.querySelector('.icon');

                if (repliesContainer) {
                    const isShown = repliesContainer.style.display === 'block';
                    repliesContainer.style.display = isShown ? 'none' : 'block';

                    if (icon) {
                        icon.classList.toggle('fa-chevron-down', !isShown);
                        icon.classList.toggle('fa-chevron-up', isShown);
                    }
                }
            });
        });
    });
</script>