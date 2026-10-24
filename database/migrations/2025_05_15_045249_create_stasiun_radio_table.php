<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stasiun_radio', function (Blueprint $table) {
            $table->id();
            $table->String('nama_stasiun');
            $table->String('nama_daerah');
            $table->String('url_stasiun');
            $table->String('thumbnail');
            $table->date('tanggal_upload');
            $table->date('jam_upload');
            $table->String('deskripsi');
            $table->bigInteger('jumlah_pendengar');
            $table->boolean('is_archieved')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stasiun_radio');
    }
};
