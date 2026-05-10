<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Bloque;
use App\Models\Pieza;

class PiezaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Bloque $bloque)
    {
        $consulta = $bloque->piezas()->with('ultimoRegistro');

        if ($request->filled('buscar')) {
            $consulta->where('nombre', 'ilike', "%{$request->buscar}%");
        }

        $piezas = $consulta->paginate($request->input('por_pagina', 15));

        return response()->json($piezas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Bloque $bloque)
    {
        $datosValidados = $request->validate([
            'nombre'       => 'required|string|max:255',
            'codigo_pieza' => 'required|string|unique:piezas,codigo_pieza',
            'descripcion'  => 'nullable|string',
            'peso_teorico' => 'required|numeric|min:0.001',
        ]);

        $pieza = $bloque->piezas()->create($datosValidados);

        return response()->json([
            'mensaje' => 'Pieza creada correctamente.',
            'pieza'   => $pieza->load('bloque.proyecto'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bloque $bloque, Pieza $pieza)
    {
        abort_if($pieza->bloque_id !== $bloque->id, 404, 'Pieza no encontrada en este bloque.');

        $pieza->load(['bloque.proyecto', 'registrosFabricacion']);

        return response()->json($pieza);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bloque $bloque, Pieza $pieza)
    {
        abort_if($pieza->bloque_id !== $bloque->id, 404, 'Pieza no encontrada en este bloque.');

        $datosValidados = $request->validate([
            'nombre'       => 'sometimes|string|max:255',
            'codigo_pieza' => 'sometimes|string|unique:piezas,codigo_pieza,' . $pieza->id,
            'descripcion'  => 'nullable|string',
            'peso_teorico' => 'sometimes|numeric|min:0.001',
        ]);

        $pieza->update($datosValidados);

        return response()->json([
            'mensaje' => 'Pieza actualizada correctamente.',
            'pieza'   => $pieza,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bloque $bloque, Pieza $pieza)
    {
        abort_if($pieza->bloque_id !== $bloque->id, 404, 'Pieza no encontrada en este bloque.');

        $pieza->delete();

        return response()->json([
            'mensaje' => 'Pieza eliminada correctamente.',
        ]);
    }
}
