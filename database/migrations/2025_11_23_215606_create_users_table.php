<?php
// database/migrations/xxxx_xx_xx_000003_create_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('sexe')->nullable();
            $table->date('date_naissance')->nullable();
            $table->date('date_inscription')->default(now());
            $table->string('statut')->default('actif');
            $table->string('photo')->nullable();
            $table->foreignId('role_id')->default(1)->constrained('roles');
            $table->foreignId('langue_id')->default(1)->constrained('langues');
            $table->string('two_factor_secret')->nullable();
            $table->string('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
