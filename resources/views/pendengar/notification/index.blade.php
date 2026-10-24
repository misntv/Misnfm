@extends('pendengar.layouts.app')
@section('title', 'Notifikasi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Daftar Notifikasi</h4>
        <div>
            <form action="{{ route('pendengar.notifications.readAll') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-success">✓ Tandai semua telah dibaca</button>
            </form>
            <form action="{{ route('pendengar.notifications.deleteAll') }}" method="POST" class="d-inline ms-2">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">🗑️ Hapus semua</button>
            </form>
        </div>
    </div>

    <div class="list-group shadow-sm">
        @forelse($notifications as $notif)
            <a href="{{ route('pendengar.notifications.read', $notif->id) }}"
               class="list-group-item list-group-item-action {{ is_null($notif->read_at) ? 'list-group-item-primary' : '' }}">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1"><i class="{{ $notif->data['icon'] ?? 'fas fa-bell' }}"></i> {{ $notif->data['title'] ?? 'Notifikasi' }}</h6>
                    <small>{{ $notif->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1">{{ $notif->data['body'] ?? '-' }}</p>
            </a>
        @empty
            <div class="text-center text-muted p-4">Tidak ada notifikasi</div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection