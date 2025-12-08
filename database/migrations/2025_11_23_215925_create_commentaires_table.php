<?php
// database/migrations/xxxx_xx_xx_000009_create_commentaires_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->text('texte');
            $table->integer('note')->nullable();
            $table->dateTime('date')->default(now());
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('contenu_id')->constrained('contenus');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commentaires');
    }
};
