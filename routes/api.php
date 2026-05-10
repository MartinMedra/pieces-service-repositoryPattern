<?php

use App\Http\Controllers\Api\V1\ProyectoController;
use App\Http\Controllers\Api\V1\BloqueController;
use App\Http\Controllers\Api\V1\PiezaController;
use App\Http\Controllers\Api\V1\RegistroFabricacionController;
use App\Http\Controllers\Api\V1\ReporteController;
use Illuminate\Support\Facades\Route;


Route::middleware('jwt.verificacion')->prefix('v1')->group(function () {


    Route::get('proyectos', [ProyectoController::class, 'index'])->name('proyectos.index');
    Route::post('proyectos', [ProyectoController::class, 'store'])->name('proyectos.store');
    Route::get('proyectos/{proyecto}', [ProyectoController::class, 'show'])->name('proyectos.show');
    Route::match(['put', 'patch'], 'proyectos/{proyecto}', [ProyectoController::class, 'update'])->name('proyectos.update');
    Route::delete('proyectos/{proyecto}', [ProyectoController::class, 'destroy'])->name('proyectos.destroy');


    Route::get('proyectos/{proyecto}/bloques', [BloqueController::class, 'index'])->name('proyectos.bloques.index');
    Route::post('proyectos/{proyecto}/bloques', [BloqueController::class, 'store'])->name('proyectos.bloques.store');
    Route::get('proyectos/{proyecto}/bloques/{bloque}', [BloqueController::class, 'show'])->name('proyectos.bloques.show');
    Route::match(['put', 'patch'], 'proyectos/{proyecto}/bloques/{bloque}', [BloqueController::class, 'update'])->name('proyectos.bloques.update');
    Route::delete('proyectos/{proyecto}/bloques/{bloque}', [BloqueController::class, 'destroy'])->name('proyectos.bloques.destroy');


    Route::get('bloques/{bloque}/piezas', [PiezaController::class, 'index'])->name('bloques.piezas.index');
    Route::post('bloques/{bloque}/piezas', [PiezaController::class, 'store'])->name('bloques.piezas.store');
    Route::get('bloques/{bloque}/piezas/{pieza}', [PiezaController::class, 'show'])->name('bloques.piezas.show');
    Route::match(['put', 'patch'], 'bloques/{bloque}/piezas/{pieza}', [PiezaController::class, 'update'])->name('bloques.piezas.update');
    Route::delete('bloques/{bloque}/piezas/{pieza}', [PiezaController::class, 'destroy'])->name('bloques.piezas.destroy');

    
    Route::get('piezas/{pieza}/registros', [RegistroFabricacionController::class, 'index'])->name('piezas.registros.index');
    Route::post('piezas/{pieza}/registros', [RegistroFabricacionController::class, 'store'])->name('piezas.registros.store');
    Route::get('piezas/{pieza}/registros/{registro}', [RegistroFabricacionController::class, 'show'])->name('piezas.registros.show');
    Route::match(['put', 'patch'], 'piezas/{pieza}/registros/{registro}', [RegistroFabricacionController::class, 'update'])->name('piezas.registros.update');

    Route::prefix('reportes')->group(function () {
        Route::get('piezas-pendientes', [ReporteController::class, 'piezasPendientesPorProyecto']);
        Route::get('totales-por-estado', [ReporteController::class, 'totalesPorEstado']);
    });
});
