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
        Schema::create('radio_listener_stats', function (Blueprint $table) {
    $table->id();
    $table->foreignId('radio_id')->constrained('stasiun_radio');
    $table->date('stat_date')->index();  // Menyimpan tanggal statistik
    $table->integer('total_listeners')->default(0);  // Total harian
    $table->json('hourly_listeners')->nullable();  // Detail per jam
    $table->timestamps();
    
    $table->unique(['radio_id', 'stat_date']);  // Hindari duplikasi data
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radio_listener_stats');
    }
};
