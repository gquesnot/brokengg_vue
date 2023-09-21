<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summoner_match_frame_events', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\SummonerMatch::class, 'summoner_match_id');
            $table->foreignIdFor(\App\Models\SummonerMatchFrame::class, 'summoner_match_frame_id');
            $table->bigInteger('current_timestamp');
            $table->string('type');

            //item purchases && sold && destroyed
            $table->foreignIdFor(\App\Models\Item::class, 'item_id')->nullable();

            // kills && victims
            $table->foreignIdFor(\App\Models\SummonerMatch::class, 'summoner_match_victim_id')->nullable();
            $table->foreignIdFor(\App\Models\SummonerMatchFrame::class, 'summoner_match_frame_victim_id')->nullable();
            $table->integer('position_x')->nullable();
            $table->integer('position_y')->nullable();

            // skill level ups
            $table->integer('skill_slot')->nullable();
            $table->string('level_up_type')->nullable();

            // level up
            $table->integer('level')->nullable();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summoner_match_frame_event_items');
    }
};
