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
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('description');
        });
        Schema::create('modes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
        });
        Schema::create('champions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('img_url');
            $table->json('stats')->nullable();
        });
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->json('tags');
            $table->string('img_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('queues');
        Schema::dropIfExists('modes');
        Schema::dropIfExists('champions');
        Schema::dropIfExists('items');
    }
};
