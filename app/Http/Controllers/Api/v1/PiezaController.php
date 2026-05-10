<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Bloque;
use App\Models\Pieza;
use App\Repositories\Contracts\PiezaRepositoryInterface;
use Illuminate\Http\Request;

class PiezaController extends Controller
{
    public function __construct(
        private readonly PiezaRepositoryInterface $repositorio
    ) {}

    public function index(Request $request, Bloque $bloque)
    {
        $piezas = $this->repositorio->obtenerPorBloque(
            bloque:    $bloque,
            filtros:   $request->only(['buscar']),
            porPagina: (int) $request->input('por_pagina', 15)
        );

        return response()->json($piezas);
    }

    public function store(Request $request, Bloque $bloque)
    {
        $datosValidados = $request->validate([
            'nombre'       => ['required', 'string', 'max:255'],
            'codigo_pieza' => ['required', 'string', 'unique:piezas,codigo_pieza'],
            'descripcion'  => ['nullable', 'string'],
            'peso_teorico' => ['required', 'numeric', 'min:0.001'],
        ]);

        $pieza = $this->repositorio->crear($bloque, $datosValidados);

        return response()->json([
            'mensaje' => 'Pieza creada correctamente.',
            'pieza'   => $pieza->load('bloque.proyecto'),
        ], 201);
    }

    public function show(Bloque $bloque, Pieza $pieza)
    {
        abort_if(
            $pieza->bloque_id !== $bloque->id,
            404,
            'Pieza no encontrada en este bloque.'
        );

        return response()->json(
            $pieza->load(['bloque.proyecto', 'registrosFabricacion'])
        );
    }

    public function update(Request $request, Bloque $bloque, Pieza $pieza)
    {
        abort_if(
            $pieza->bloque_id !== $bloque->id,
            404,
            'Pieza no encontrada en este bloque.'
        );

        $datosValidados = $request->validate([
            'nombre'       => ['sometimes', 'string', 'max:255'],
            'codigo_pieza' => ['sometimes', 'string', 'unique:piezas,codigo_pieza,' . $pieza->id],
            'descripcion'  => ['nullable', 'string'],
            'peso_teorico' => ['sometimes', 'numeric', 'min:0.001'],
        ]);

        $piezaActualizada = $this->repositorio->actualizar($pieza, $datosValidados);

        return response()->json([
            'mensaje' => 'Pieza actualizada correctamente.',
            'pieza'   => $piezaActualizada,
        ]);
    }

    public function destroy(Bloque $bloque, Pieza $pieza)
    {
        abort_if(
            $pieza->bloque_id !== $bloque->id,
            404,
            'Pieza no encontrada en este bloque.'
        );

        $this->repositorio->eliminar($pieza);

        return response()->json([
            'mensaje' => 'Pieza eliminada correctamente.',
        ]);
    }
}
