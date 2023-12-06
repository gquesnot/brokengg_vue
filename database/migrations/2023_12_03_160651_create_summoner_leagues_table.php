<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('summoner_leagues', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('tier');
            $table->string('rank');
            $table->integer('overall_position')->nullable();
            $table->foreignIdFor(\App\Models\Summoner::class, 'summoner_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summoner_leagues');
    }
};
