<?php
// database/migrations/xxxx_xx_xx_000010_create_parler_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parler', function (Blueprint $table) {
            $table->foreignId('region_id')->constrained('regions');
            $table->foreignId('langue_id')->constrained('langues');
            $table->primary(['region_id', 'langue_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parler');
    }
};
