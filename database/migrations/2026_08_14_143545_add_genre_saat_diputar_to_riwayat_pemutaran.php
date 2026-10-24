<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('riwayat_pemutaran', function (Blueprint $table) {
            $table->string('genre_saat_diputar')->nullable()->after('durasi');
        });
    }

    public function down()
    {
        Schema::table('riwayat_pemutaran', function (Blueprint $table) {
            $table->dropColumn('genre_saat_diputar');
        });
    }
};