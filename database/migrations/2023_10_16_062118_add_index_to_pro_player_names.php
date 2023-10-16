<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pro_player_names', function (Blueprint $table) {
            $table->index('summoner_name', 'summoner_name_index');
        });
    }

    public function down(): void
    {
        Schema::table('pro_player_names', function (Blueprint $table) {
            $table->dropIndex('summoner_name_index');
        });
    }
};
