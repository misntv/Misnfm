<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserAdmin;
use App\Http\Controllers\UserWartawan;
use App\Http\Controllers\UserPendengar;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\BacaController;
use App\Http\Controllers\RadioController;
use App\Http\Controllers\RadioPlayerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Wartawan\DashboardWartawanController;
use App\Http\Controllers\Admin\BeritaVerifikasiController;
use App\Http\Controllers\BerandaBeritaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Wartawan\WartawanNotificationController;
use App\Http\Controllers\Pendengar\PendengarNotificationController;
use App\Http\Controllers\Admin\ProfileAdminController;
use App\Http\Controllers\Pendengar\ProfilePendengarController;
use App\Http\Controllers\Wartawan\ProfileWartawanController;
use App\Http\Controllers\Pendengar\RekomendasiController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


// =============================
// Landing / Login
// =============================
Route::get('/', function () {
    return view('auth.login');
})->name('auth.login');

// =============================
// Auth Routes
// =============================
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Google Auth
Route::get('/login/google', [UserController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/login/google/callback', [UserController::class, 'handleGoogleCallback']);

// =============================
// Registrasi
// =============================
Route::get('/register/select-role', [RegisterController::class, 'showRoleSelection'])->name('register.select-role');
Route::get('/register/{userType}', [RegisterController::class, 'showRegistrationForm'])
    ->name('register.form')->where('userType', 'wartawan|pendengar');
Route::post('/register/{userType}', [RegisterController::class, 'register'])
    ->name('register.submit')->where('userType', 'wartawan|pendengar');
Route::get('/register/success', [RegisterController::class, 'showSuccess'])->name('register.success');

// =============================
// Admin
// =============================
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    // Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard.admin');
        Route::get('/dashboard', 'index')->name('dashboard');
    });

    // Profil Admin
    Route::get('/profile', [ProfileAdminController::class, 'show'])->name('profile.show');

    // Verifikasi Wartawan
    Route::controller(UserAdmin::class)->group(function () {
        Route::get('/verifikasi-wartawan', 'verifikasiWartawan')->name('verifikasi.wartawan');
        Route::post('/approve-wartawan/{id}', 'approveWartawan')->name('approve.wartawan');
        Route::post('/reject-wartawan/{id}', 'rejectWartawan')->name('reject.wartawan');
    });

    // Verifikasi Berita
    Route::prefix('berita')->controller(BeritaVerifikasiController::class)->group(function () {
        Route::get('/verifikasi', 'index')->name('berita.verifikasi');
        Route::put('/{berita}/terima', 'terima')->name('berita.terima');
        Route::put('/{berita}/tolak', 'tolak')->name('berita.tolak');
        Route::get('/{berita}', 'show')->name('berita.show');
    });

    Route::prefix('notifications')->name('notifications.')->controller(AdminNotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('{id}/read', 'markAsRead')->name('read');
        Route::post('read/all', 'markAllAsRead')->name('read.all');
        Route::delete('delete/all', 'deleteAll')->name('delete.all');
    });


});

// =============================
// Wartawan
// =============================
Route::prefix('wartawan')->middleware('auth')->name('wartawan.')->group(function () {
    Route::get('/', [DashboardWartawanController::class, 'index'])->name('dashboard.wartawan');
    Route::get('/profile', [ProfileWartawanController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileWartawanController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileWartawanController::class, 'update'])->name('profile.update');

    Route::prefix('notifications')->name('notifications.')
    ->controller(WartawanNotificationController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('{id}/read', 'markAsRead')->name('read');
        Route::post('read/all', 'markAllAsRead')->name('readAll');
        Route::delete('delete/all', 'deleteAll')->name('deleteAll');
    });
});

// =============================
// Pendengar
// =============================
Route::prefix('pendengar')->name('pendengar.')->group(function () {
    // Public pages
    Route::get('/radio', [RadioController::class, 'index'])->name('radio');
    Route::get('/profile', [ProfilePendengarController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfilePendengarController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfilePendengarController::class, 'update'])->name('profile.update');
    Route::get('/player/{id}', [RadioController::class, 'player'])->name('player.index');
    Route::get('/radio/daerah/{daerah}', [RadioController::class, 'byRegion'])->name('radio.byRegion');

    // ✅ REKOMENDASI - Tambahkan di sini (sebelum middleware auth)
    Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
    Route::post('/rekomendasi/refresh', [RekomendasiController::class, 'refresh'])->name('rekomendasi.refresh');
    Route::get('/rekomendasi/json', [RekomendasiController::class, 'getRecommendationsJson'])->name('rekomendasi.json');

    // Berita untuk pendengar
    Route::get('/berita', [BerandaBeritaController::class, 'index'])->name('berita.index');
    Route::get('/baca/{id}', [BacaController::class, 'show'])->name('baca.show');

    // Area login
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [UserPendengar::class, 'dashboard'])->name('dashboard');
        Route::get('/radio', [RadioController::class, 'index'])->name('radio.index');
        
        // ✅ ROUTE RIWAYAT PEMUTARAN - Tambahkan di sini
        Route::post('/riwayat/play', [RadioController::class, 'storePlayHistory'])->name('riwayat.play');
        Route::post('/riwayat/stop/{id}', [RadioController::class, 'updatePlayDuration'])->name('riwayat.stop');
    });

    Route::prefix('berita')->name('berita.')->group(function () {
        Route::get('/', [BerandaBeritaController::class, 'index'])->name('index');
        Route::get('/{id}', [BerandaBeritaController::class, 'show'])->name('show');
    });
    
    Route::get('/notifikasi', [PendengarNotificationController::class, 'index'])->name('notifications');
    Route::get('/notifikasi/baca/{id}', [PendengarNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifikasi/baca-semua', [PendengarNotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifikasi/hapus-semua', [PendengarNotificationController::class, 'deleteAll'])->name('notifications.deleteAll');
});


// =============================
// Berita (Wartawan / Admin)
// =============================
Route::prefix('berita')->middleware('auth')->name('berita.')->group(function () {
    Route::get('/archived', [BeritaController::class, 'archived'])->name('archived');
    Route::post('/{id}/archive', [BeritaController::class, 'archive'])->name('archive');
    Route::post('/{id}/restore', [BeritaController::class, 'restore'])->name('restore');
    Route::get('/{id}/edit', [BeritaController::class, 'edit'])->name('edit');
    Route::get('/arsip/{id}', [BeritaController::class, 'showarsip'])->name('showarsip');
});
Route::resource('berita', BeritaController::class)->middleware('auth');

// =============================
// Playlist
// =============================
Route::prefix('playlist')->middleware('auth')->name('playlist.')->group(function () {
    Route::get('/archived', [PlaylistController::class, 'archived'])->name('archived');
    Route::post('/{id}/archive', [PlaylistController::class, 'archive'])->name('archive');
    Route::post('/{id}/restore', [PlaylistController::class, 'restore'])->name('restore');
    Route::get('/player/{id}', [PlaylistController::class, 'player'])->name('player');
    Route::post('/increment/{id}', [PlaylistController::class, 'incrementListener'])->name('increment');
    Route::get('/statistics', [PlaylistController::class, 'statistics'])->name('statistics');
    Route::get('/search', [PlaylistController::class, 'search'])->name('search');
    Route::post('/cek-rss', [PlaylistController::class, 'cekRss'])->name('cekRss');
    Route::post('/cek-jadwal', [PlaylistController::class, 'cekJadwal'])->name('cekJadwal');
    Route::get('/{id}/jadwal', [App\Http\Controllers\Admin\JadwalController::class, 'index'])->name('jadwal');
    Route::post('/{id}/jadwal', [App\Http\Controllers\Admin\JadwalController::class, 'store'])->name('jadwal.store');
    Route::delete('/jadwal/{id}', [App\Http\Controllers\Admin\JadwalController::class, 'destroy'])->name('jadwal.destroy');
});
Route::resource('playlist', PlaylistController::class)->parameters(['playlist' => 'playlist'])->middleware('auth');

// =============================
// Profile & Radio Player
// =============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    
    Route::prefix('radio')->group(function () {
        Route::get('/play', [RadioPlayerController::class, 'index'])->name('radio.play');
        // Tambahan route radio lainnya
        // Route::post('/play', [RadioPlayerController::class, 'store'])->name('radio.store');
    });
});
// =============================
// Komentar Berita
// =============================
Route::middleware(['auth', 'web'])->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});