<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summoner_matchs', function (Blueprint $table) {
            $table->id();
            $table->boolean('won')->default(false);
            $table->float('kill_participation')->nullable();
            $table->integer('champ_level');
            $table->float('kda')->nullable();
            $table->integer('assists');
            $table->integer('deaths');
            $table->integer('kills');
            $table->integer('minions_killed');
            $table->integer('largest_killing_spree');
            $table->foreignIdFor(\App\Models\Champion::class, 'champion_id')->index()->constrained();
            $table->foreignIdFor(\App\Models\Summoner::class, 'summoner_id')->index()->constrained();
            $table->foreignIdFor(\App\Models\LolMatch::class, 'match_id')->index()->constrained('lol_matchs')->onDelete('cascade');
            $table->unsignedInteger('double_kills');
            $table->unsignedInteger('triple_kills');
            $table->unsignedInteger('quadra_kills');
            $table->unsignedInteger('penta_kills');
            $table->unsignedInteger('total_damage_dealt_to_champions');
            $table->unsignedInteger('gold_earned');
            $table->unsignedInteger('total_damage_taken');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('summoner_matchs');
    }
};
