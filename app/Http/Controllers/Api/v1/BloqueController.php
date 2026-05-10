<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Bloque;
use App\Models\Proyecto;
use App\Repositories\Contracts\BloqueRepositoryInterface;
use Illuminate\Http\Request;

class BloqueController extends Controller
{
    public function __construct(
        private readonly BloqueRepositoryInterface $repositorio
    ) {}

    public function index(Request $request, Proyecto $proyecto)
    {
        $bloques = $this->repositorio->obtenerPorProyecto(
            proyecto:  $proyecto,
            filtros:   $request->only(['buscar']),
            porPagina: (int) $request->input('por_pagina', 15)
        );

        return response()->json($bloques);
    }

    public function store(Request $request, Proyecto $proyecto)
    {
        $datosValidados = $request->validate([
            'nombre'        => ['required', 'string', 'max:255'],
            'descripcion'   => ['nullable', 'string'],
            'codigo_bloque' => ['required', 'string', 'unique:bloques,codigo_bloque'],
        ]);

        $bloque = $this->repositorio->crear($proyecto, $datosValidados);

        return response()->json([
            'mensaje' => 'Bloque creado correctamente.',
            'bloque'  => $bloque->load('proyecto'),
        ], 201);
    }

    public function show(Proyecto $proyecto, Bloque $bloque)
    {
        abort_if(
            $bloque->proyecto_id !== $proyecto->id,
            404,
            'Bloque no encontrado en este proyecto.'
        );

        return response()->json($bloque->load('piezas'));
    }

    public function update(Request $request, Proyecto $proyecto, Bloque $bloque)
    {
        abort_if(
            $bloque->proyecto_id !== $proyecto->id,
            404,
            'Bloque no encontrado en este proyecto.'
        );

        $datosValidados = $request->validate([
            'nombre'        => ['sometimes', 'string', 'max:255'],
            'descripcion'   => ['nullable', 'string'],
            'codigo_bloque' => ['sometimes', 'string', 'unique:bloques,codigo_bloque,' . $bloque->id],
        ]);

        $bloqueActualizado = $this->repositorio->actualizar($bloque, $datosValidados);

        return response()->json([
            'mensaje' => 'Bloque actualizado correctamente.',
            'bloque'  => $bloqueActualizado,
        ]);
    }

    public function destroy(Proyecto $proyecto, Bloque $bloque)
    {
        abort_if(
            $bloque->proyecto_id !== $proyecto->id,
            404,
            'Bloque no encontrado en este proyecto.'
        );

        $this->repositorio->eliminar($bloque);

        return response()->json([
            'mensaje' => 'Bloque eliminado correctamente.',
        ]);
    }
}
