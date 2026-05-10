<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Proyecto;
use App\Models\Bloque;

class BloqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Proyecto $proyecto)
    {
        $consulta = $proyecto->bloques()->withCount('piezas');

        if ($request->filled('buscar')) {
            $consulta->where('nombre', 'ilike', "%{$request->buscar}%");
        }

        $bloques = $consulta->paginate($request->input('por_pagina', 15));

        return response()->json($bloques);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Proyecto $proyecto)
    {
        $datosValidados = $request->validate([
            'nombre'        => 'required|string|max:255',
            'descripcion'   => 'nullable|string',
            'codigo_bloque' => 'required|string|unique:bloques,codigo_bloque',
        ]);

        $bloque = $proyecto->bloques()->create($datosValidados);

        return response()->json([
            'mensaje' => 'Bloque creado correctamente.',
            'bloque'  => $bloque->load('proyecto'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Proyecto $proyecto, Bloque $bloque)
    {
        abort_if($bloque->proyecto_id !== $proyecto->id, 404, 'Bloque no encontrado en este proyecto.');

        $bloque->load('piezas');

        return response()->json($bloque);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proyecto $proyecto, Bloque $bloque)
    {
        abort_if($bloque->proyecto_id !== $proyecto->id, 404, 'Bloque no encontrado en este proyecto.');

        $datosValidados = $request->validate([
            'nombre'        => 'sometimes|string|max:255',
            'descripcion'   => 'nullable|string',
            'codigo_bloque' => 'sometimes|string|unique:bloques,codigo_bloque,' . $bloque->id,
        ]);

        $bloque->update($datosValidados);

        return response()->json([
            'mensaje' => 'Bloque actualizado correctamente.',
            'bloque'  => $bloque,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proyecto $proyecto, Bloque $bloque)
    {
        abort_if($bloque->proyecto_id !== $proyecto->id, 404, 'Bloque no encontrado en este proyecto.');

        $bloque->delete();

        return response()->json([
            'mensaje' => 'Bloque eliminado correctamente.',
        ]);
    }
}
