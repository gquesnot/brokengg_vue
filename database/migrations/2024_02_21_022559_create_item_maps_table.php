<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('item_maps', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Map::class)->constrained();
            $table->foreignIdFor(\App\Models\Item::class)->constrained();
            $table->primary(['map_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_maps');
    }
};
