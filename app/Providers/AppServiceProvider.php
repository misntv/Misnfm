<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Berita;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('admin.layouts.sidebar', function ($view) {
            $countBeritaPengajuan = Berita::where('status_unggah', 'pengajuan')->count();
            $view->with('countBeritaPengajuan', $countBeritaPengajuan);
        });
    }

    
}
