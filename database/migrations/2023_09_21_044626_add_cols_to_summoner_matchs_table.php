<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('summoner_matchs', function (Blueprint $table) {
            $table->integer('wards_placed')->nullable();
            $table->integer('summoner_spell1_id');
            $table->integer('summoner_spell2_id');
        });
    }

    public function down(): void
    {
        Schema::table('summoner_matchs', function (Blueprint $table) {
            $table->dropColumn('wards_placed');
            $table->dropColumn('summoner_spell1_id');
            $table->dropColumn('summoner_spell2_id');
        });
    }
};
