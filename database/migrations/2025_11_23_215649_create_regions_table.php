<?php
// database/migrations/xxxx_xx_xx_000004_create_regions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('nom_region', 100);
            $table->text('description')->nullable();
            $table->integer('population')->nullable();
            $table->decimal('superficie', 10, 2)->nullable();
            $table->string('localisation', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('regions');
    }
};
