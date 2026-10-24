@extends('admin.layouts.app')

@section('title', 'Detail - ' . $playlist->nama_stasiun)

@section('content')
<div class="container-fluid px-4 my-2">
    <div class="card shadow">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <img src="{{ asset('storage/thumbnails/'.$playlist->thumbnail) }}"
                         alt="{{ $playlist->nama_stasiun }}" 
                         class="img-fluid rounded">
                </div>
                <div class="col-md-8">
                    <h3>{{ $playlist->nama_stasiun }}</h3>
                    <p><strong>URL:</strong> 
                        <a href="{{ $playlist->url_stasiun }}" target="_blank" class="text-primary">
                            {{ $playlist->url_stasiun }}
                        </a>
                    </p>
                    <p><strong>Pendengar:</strong> {{ number_format($playlist->jumlah_pendengar, 0, ',', '.') }}</p>
                    <p><strong>Tanggal Upload:</strong> 
                        {{ \Carbon\Carbon::parse($playlist->tanggal_upload)->translatedFormat('d F Y') }}
                    </p>
                    <p><strong>Deskripsi:</strong> {{ $playlist->deskripsi }}</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('playlist.player', $playlist->id) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-play"></i> Putar Radio
                        </a>
                        <a href="{{ route('playlist.edit', $playlist->id) }}" 
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('playlist.archive', $playlist->id) }}" 
                              method="POST" 
                              style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-archive"></i> Arsipkan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('playlist.index') }}" class="btn btn-kembali me-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    </div>
@endsection