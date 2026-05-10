<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pieza;
use App\Models\Bloque;
use App\Models\RegistroFabricacion;

class RegistroFabricacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Pieza $pieza)
    {
        $consulta = $pieza->registrosFabricacion();

        if ($request->filled('estado')) {
            $consulta->where('estado', $request->estado);
        }

        if ($request->filled('fecha_desde')) {
            $consulta->whereDate('fecha_fabricacion', '>=', $request->fecha_desde);
        }
        if ($request->filled('fecha_hasta')) {
            $consulta->whereDate('fecha_fabricacion', '<=', $request->fecha_hasta);
        }

        $registros = $consulta->latest('fecha_fabricacion')
                              ->paginate($request->input('por_pagina', 15));

        return response()->json($registros);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Pieza $pieza)
    {
        $datosValidados = $request->validate([
            'peso_real'      => 'required|numeric|min:0.001',
            'estado'         => 'required|in:pendiente,fabricada',
            'observaciones'  => 'nullable|string|max:500',
        ]);

        $registro = $pieza->registrosFabricacion()->create([
            'peso_teorico'  => $pieza->peso_teorico,
            'peso_real'     => $datosValidados['peso_real'],
            'estado'        => $datosValidados['estado'],
            'observaciones' => $datosValidados['observaciones'] ?? null,
            'usuario_id'    => $request->usuario_id,
        ]);

        return response()->json([
            'mensaje'  => 'Registro de fabricación creado correctamente.',
            'registro' => $registro->load('pieza'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pieza $pieza, RegistroFabricacion $registro)
    {
        abort_if($registro->pieza_id !== $pieza->id, 404, 'Registro no encontrado para esta pieza.');

        return response()->json($registro->load('pieza.bloque.proyecto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pieza $pieza, RegistroFabricacion $registro)
    {
        abort_if($registro->pieza_id !== $pieza->id, 404, 'Registro no encontrado para esta pieza.');

        $datosValidados = $request->validate([
            'peso_real'     => 'sometimes|numeric|min:0.001',
            'estado'        => 'sometimes|in:pendiente,fabricada',
            'observaciones' => 'nullable|string|max:500',
        ]);

        $registro->update($datosValidados);

        return response()->json([
            'mensaje'  => 'Registro actualizado correctamente.',
            'registro' => $registro,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
