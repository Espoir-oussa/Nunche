<?php
// database/migrations/xxxx_xx_xx_000006_create_contenus_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contenus', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 255);
            $table->text('texte');
            $table->dateTime('date_creation')->default(now());
            $table->string('statut');
            $table->dateTime('date_validation')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('contenus');
            $table->foreignId('region_id')->constrained('regions');
            $table->foreignId('langue_id')->constrained('langues');
            $table->foreignId('moderateur_id')->nullable()->constrained('users');
            $table->foreignId('type_contenu_id')->constrained('type_contenus');
            $table->foreignId('auteur_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('contenus');
    }
};
