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
        Schema::create('registros_fabricacion', function (Blueprint $tabla) {
            $tabla->id();
            $tabla->foreignId('pieza_id')
                  ->constrained('piezas')
                  ->onDelete('cascade');
            $tabla->timestamp('fecha_fabricacion')->useCurrent();
            $tabla->decimal('peso_teorico', 10, 3);
            $tabla->decimal('peso_real', 10, 3);
            $tabla->decimal('diferencia_peso', 10, 3)
                  ->storedAs('peso_real - peso_teorico');
            $tabla->enum('estado', ['pendiente', 'fabricada'])->default('pendiente');
            $tabla->text('observaciones')->nullable();
            $tabla->unsignedBigInteger('usuario_id');
            $tabla->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros_fabricacion');
    }
};
