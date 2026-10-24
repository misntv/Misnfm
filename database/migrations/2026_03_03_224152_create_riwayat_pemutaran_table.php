<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('riwayat_pemutaran', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->unsignedBigInteger('stasiun_radio_id');
        $table->timestamp('played_at')->useCurrent();
        $table->integer('durasi')->nullable(); 
        $table->timestamps();

        $table->foreign('user_id')
              ->references('id')
              ->on('user_misnfm')
              ->onDelete('cascade');

        $table->foreign('stasiun_radio_id')
              ->references('id')
              ->on('stasiun_radio')
              ->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pemutaran');
    }
};
