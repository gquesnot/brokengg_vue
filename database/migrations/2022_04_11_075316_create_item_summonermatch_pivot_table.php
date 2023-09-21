<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemSummonermatchPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('summoner_match_items', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Item::class, 'item_id')->index()->constrained();
            $table->foreignIdFor(\App\Models\SummonerMatch::class, 'summoner_match_id')->index()->constrained('summoner_matchs')->onDelete('cascade');
            $table->integer('position');
            $table->primary(['item_id', 'summoner_match_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_summoner_matchs');
    }
}
