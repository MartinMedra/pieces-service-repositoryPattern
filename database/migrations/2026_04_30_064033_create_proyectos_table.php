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
        Schema::create('proyectos', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->string('nombre');
            $tabla->text('descripcion')->nullable();
            $tabla->string('codigo_proyecto')->unique(); //codigo interno de proyecto nval
            $tabla->enum('estado', ['activo', 'inactivo'])->default('activo');
            $tabla->timestamps();
            $tabla->softDeletes(); //Para que no borre fisicamente
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
