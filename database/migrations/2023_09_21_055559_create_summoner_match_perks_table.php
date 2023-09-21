<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summoner_match_perks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\SummonerMatch::class, 'summoner_match_id');
            $table->foreignIdFor(\App\Models\Perk::class, 'defense_id');
            $table->foreignIdFor(\App\Models\Perk::class, 'flex_id');
            $table->foreignIdFor(\App\Models\Perk::class, 'offense_id');
            $table->foreignIdFor(\App\Models\Perk::class, 'primary_style_id');
            $table->foreignIdFor(\App\Models\Perk::class, 'primary_style1_id');
            $table->foreignIdFor(\App\Models\Perk::class, 'primary_style2_id');
            $table->foreignIdFor(\App\Models\Perk::class, 'primary_style3_id');
            $table->foreignIdFor(\App\Models\Perk::class, 'sub_style_id');
            $table->foreignIdFor(\App\Models\Perk::class, 'sub_style1_id');
            $table->foreignIdFor(\App\Models\Perk::class, 'sub_style2_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summoner_match_perks');
    }
};
