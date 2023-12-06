<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('summoners', function (Blueprint $table) {
            $table->string('tag_line')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('summoners', function (Blueprint $table) {
            $table->dropColumn('tag_line');
        });
    }
};
