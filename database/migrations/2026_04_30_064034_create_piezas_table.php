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
        Schema::create('piezas', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->foreignId('bloque_id')
                  ->constrained('bloques')
                  ->onDelete('cascade');
            $tabla->string('nombre');
            $tabla->string('codigo_pieza')->unique();
            $tabla->text('descripcion')->nullable();
            $tabla->decimal('peso_teorico', 10, 3); //para guardar los kg con 3 decimales
            $tabla->timestamps();
            $tabla->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piezas');
    }
};
