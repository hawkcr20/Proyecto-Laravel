<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {

            $table->id('idReserva');

            
            $table->foreignId('idUsuario')
                ->constrained('usuarios')
                ->onDelete('cascade');

            
            $table->foreignId('idClase')
                ->constrained('clases')
                ->onDelete('cascade');

            $table->date('fechaReserva');
            $table->string('estado');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};