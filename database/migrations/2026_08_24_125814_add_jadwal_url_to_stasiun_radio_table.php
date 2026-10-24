<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('stasiun_radio', function (Blueprint $table) {
        $table->string('jadwal_url')->nullable()->after('feed_url');
    });
}

public function down()
{
    Schema::table('stasiun_radio', function (Blueprint $table) {
        $table->dropColumn('jadwal_url');
    });
}
};
