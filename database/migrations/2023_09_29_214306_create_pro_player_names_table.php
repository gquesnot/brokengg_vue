<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pro_player_names', function (Blueprint $table) {
            $table->id();
            $table->string('summoner_name')->unique();
            $table->foreignIdFor(\App\Models\ProPlayer::class, 'pro_player_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pro_player_names');
    }
};
