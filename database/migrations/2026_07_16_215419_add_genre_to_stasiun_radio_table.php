<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stasiun_radio', function (Blueprint $table) {
            $table->string('genre', 100)->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('stasiun_radio', function (Blueprint $table) {
            $table->dropColumn('genre');
        });
    }
};