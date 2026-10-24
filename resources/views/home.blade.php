@extends('layouts.app')

@section('content')
<div class="container">
    @auth
        <div class="alert alert-info">
            Anda akan diarahkan ke dashboard sesuai role...
        </div>
        <script>
            setTimeout(function() {
                window.location.href = "{{ 
                    auth()->user()->isAdmin() ? route('admin.dashboard.admin') : 
                    (auth()->user()->isWartawan() ? route('wartawan.dashboard.wartawan') : route('pendengar.radio.index'))
                }}";
            }, 2000);
        </script>
    @else
        <div class="alert alert-warning">
            Silakan login terlebih dahulu
        </div>
    @endauth
</div>
@endsection