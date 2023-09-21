<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summoner_match_frames', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('current_timestamp');
            $table->json('champion_stats');
            $table->json('damage_stats');
            $table->integer('current_gold');
            $table->integer('gold_per_second');
            $table->integer('total_gold');
            $table->integer('jungle_minions_killed');
            $table->integer('level');
            $table->integer('minions_killed');
            $table->integer('position_x');
            $table->integer('position_y');
            $table->integer('xp');
            $table->integer('time_enemy_spent_controlled');
            $table->foreignIdFor(\App\Models\SummonerMatch::class, 'summoner_match_id');
            $table->foreignIdFor(\App\Models\LolMatch::class, 'match_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summoner_match_frames');
    }
};
