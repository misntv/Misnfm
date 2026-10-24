@extends('admin.layouts.main')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Notifikasi</h5>
        <div>
            <a href="{{ route('admin.notifications.read.all') }}" >
                Tandai semua dibaca
            </a>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="50px">#</th>
                    <th>Notifikasi</th>
                    <th>Waktu</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notification)
                <tr class="{{ is_null($notification->read_at) ? 'table-warning' : '' }}">
                    <td>
                        <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }}"></i>
                    </td>
                    <td>
                        <div>
                            <strong class="text-primary">{{ $notification->data['title'] ?? 'Notifikasi Baru' }}</strong><br>
                            <span class="text-muted">{{ $notification->data['body'] ?? '-' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $notification->created_at->diffForHumans() }}</span>
                    </td>
                    <td>
                        <a href="{{ route('admin.notifications.read', $notification->id) }}" >
                            📄 Buka
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">Tidak ada notifikasi</td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
    @if($notifications->hasPages())
        <div class="card-footer">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection