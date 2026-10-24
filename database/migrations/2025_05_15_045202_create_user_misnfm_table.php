<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_misnfm', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('email')->unique();
            $table->string('no_identitas')->unique()->nullable();
            $table->string('asal_perusahaan')->nullable();
            $table->string('url_foto_kartu_identitas')->nullable();
            $table->string('url_foto_profil')->nullable();
            $table->string('alamat')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->string('provider_id')->nullable();
            $table->string('provider')->nullable();
            $table->string('no_telp')->nullable();
            $table->enum('role', ['admin_radio', 'pendengar', 'wartawan']);
            $table->timestamp('approved_at')->nullable(); // Tambahkan ini
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_misnfm');
    }
};