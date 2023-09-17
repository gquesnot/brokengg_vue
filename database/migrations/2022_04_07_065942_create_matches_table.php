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
        Schema::create('lol_matchs', function (Blueprint $table) {
            $table->id();
            $table->boolean('updated')->default(false);
            $table->string('match_id')->index()->unique();
            $table->foreignIdFor(\App\Models\Mode::class, 'mode_id')->index()->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Map::class, 'map_id')->index()->nullable()->constrained();
            $table->foreignIdFor(\App\Models\Queue::class, 'queue_id')->index()->nullable()->constrained();
            $table->timestamp('match_creation')->index()->nullable();
            $table->timestamp('match_end')->nullable();
            $table->time('match_duration')->nullable();
            $table->boolean('is_trashed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lol_matchs');
    }
};
