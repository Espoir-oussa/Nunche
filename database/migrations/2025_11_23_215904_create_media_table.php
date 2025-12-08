<?php
// database/migrations/xxxx_xx_xx_000008_create_media_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('chemin');
            $table->text('description')->nullable();
            $table->foreignId('contenu_id')->constrained('contenus');
            $table->foreignId('type_media_id')->constrained('type_media');
            $table->foreignId('type_contenu_id')->constrained('type_contenus');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
    }
};
