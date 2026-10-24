<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rekomendasi_cache', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->json('rekomendasi_ids'); // [1, 5, 8, 12]
            $table->timestamp('expired_at');
            $table->timestamps();

            $table->foreign('user_id')
                  ->references('id')
                  ->on('user_misnfm')
                  ->onDelete('cascade');
            
            // Index untuk query cepat
            $table->index(['user_id', 'expired_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekomendasi_cache');
    }
};