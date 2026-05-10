<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bloques', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->foreignId('proyecto_id')
                  ->constrained('proyectos')
                  ->onDelete('cascade'); // si se borra el proyecto, se borran sus bloques
            $tabla->string('nombre');
            $tabla->text('descripcion')->nullable();
            $tabla->string('codigo_bloque')->unique();
            $tabla->timestamps();
            $tabla->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloques');
    }
};
