<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id(); // 🔥 CLAVE PRIMARIA CORRECTA

            $table->string('nombre');
            $table->string('apellidoUno');
            $table->string('apellidoDos')->nullable();
            $table->string('email')->unique();
            $table->string('telefono')->nullable();
            $table->string('userName')->unique();
            $table->string('password');

            $table->unsignedBigInteger('idRol');

            $table->foreign('idRol')
                ->references('idRol')
                ->on('roles')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
