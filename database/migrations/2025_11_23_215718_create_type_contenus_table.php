<?php
// database/migrations/xxxx_xx_xx_000005_create_type_contenus_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('type_contenus', function (Blueprint $table) {
            $table->id();
            $table->string('nom_contenu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('type_contenus');
    }
};
