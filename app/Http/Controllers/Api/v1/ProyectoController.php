<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Proyecto;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $consulta = Proyecto::query();

        if ($request->filled('estado')) {
            $consulta->where('estado', $request->estado);
        }


        if ($request->filled('buscar')) {
            $consulta->where(function ($q) use ($request) {
                $q->where('nombre', 'ilike', "%{$request->buscar}%")
                  ->orWhere('codigo_proyecto', 'ilike', "%{$request->buscar}%");
            });
        }

        $proyectos = $consulta->withCount('bloques')
                              ->paginate($request->input('por_pagina', 15));

        return response()->json($proyectos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'codigo_proyecto' => 'required|string|unique:proyectos,codigo_proyecto',
            'estado'          => 'sometimes|in:activo,inactivo',
        ]);

        $proyecto = Proyecto::create($datosValidados);

        return response()->json([
            'mensaje'  => 'Proyecto creado correctamente.',
            'proyecto' => $proyecto,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Proyecto $proyecto)
    {
        $proyecto->load('bloques.piezas');

        return response()->json($proyecto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        $datosValidados = $request->validate([
            'nombre'          => 'sometimes|string|max:255',
            'descripcion'     => 'nullable|string',
            'codigo_proyecto' => 'sometimes|string|unique:proyectos,codigo_proyecto,' . $proyecto->id,
            'estado'          => 'sometimes|in:activo,inactivo',
        ]);

        $proyecto->update($datosValidados);

        return response()->json([
            'mensaje'  => 'Proyecto actualizado correctamente.',
            'proyecto' => $proyecto,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proyecto $proyecto)
    {
        $proyecto->delete();

        return response()->json([
            'mensaje' => 'Proyecto eliminado correctamente.',
        ]);
    }
}
