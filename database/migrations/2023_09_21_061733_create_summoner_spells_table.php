<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summoner_spells', function (Blueprint $table) {
            $table->id();
            $table->string('img_url');
            $table->string('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summoner_spells');
    }
};
