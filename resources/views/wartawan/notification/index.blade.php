@extends('wartawan.layouts.app')

@section('title', 'Notifikasi Wartawan')

@section('content')
<div class="container-fluid px-4 my-3">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0">Daftar Notifikasi</h5>
            <div>
                <a href="{{ route('wartawan.notifications.read.all') }}" class="btn btn-sm btn-outline-secondary">
                    Tandai semua dibaca
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50px">#</th>
                        <th>Notifikasi</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($grouped as $group => $notifs)
                        {{-- Label waktu tebal --}}
                        <tr>
                            <td colspan="4" style="font-weight: bold; font-size: 16px; background-color: #f8f9fa;">
                                {{ $group }}
                            </td>
                        </tr>

                        @foreach($notifs as $notification)
                            <tr class="{{ is_null($notification->read_at) ? 'table-warning' : '' }}">
                                <td>
                                    <i class="{{ $notification->data['icon'] ?? 'fas fa-bell' }}"></i>
                                </td>
                                <td>
                                    <div>
                                        <strong class="text-primary">{{ $notification->data['title'] ?? 'Notifikasi Baru' }}</strong><br>
                                        <span class="text-muted small">{{ $notification->data['body'] ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $notification->created_at->diffForHumans() }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('wartawan.notifications.read', $notification->id) }}" class="btn btn-sm btn-outline-primary">
                                        📄 Buka
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">Tidak ada notifikasi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection