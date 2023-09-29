<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pro_players', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('team_name')->nullable();
            $table->string('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pro_players');
    }
};
