<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul_berita');
            $table->unsignedBigInteger('penulis_id')->nullable();
            $table->string('url_gambar');
            $table->date('tanggal_kirim');
            $table->time('jam_kirim');
            $table->date('tanggal_terbit')->nullable();
            $table->time('jam_terbit')->nullable();
            $table->string('topik');
            $table->text('isi_berita');
            $table->enum('status_unggah', ['pengajuan', 'diterima', 'ditolak'])->default('pengajuan');
            $table->boolean('is_archieved')->default(false);
            $table->boolean('edited_since_archieved')->default(false);
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};