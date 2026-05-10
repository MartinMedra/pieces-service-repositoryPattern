<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pieza;
use App\Models\RegistroFabricacion;
use App\Repositories\Contracts\RegistroFabricacionRepositoryInterface;
use Illuminate\Http\Request;

class RegistroFabricacionController extends Controller
{
    public function __construct(
        private readonly RegistroFabricacionRepositoryInterface $repositorio
    ) {}

    public function index(Request $request, Pieza $pieza)
    {
        $registros = $this->repositorio->obtenerPorPieza(
            pieza:     $pieza,
            filtros:   $request->only(['estado', 'fecha_desde', 'fecha_hasta']),
            porPagina: (int) $request->input('por_pagina', 15)
        );

        return response()->json($registros);
    }

    public function store(Request $request, Pieza $pieza)
    {
        $datosValidados = $request->validate([
            'peso_real'     => ['required', 'numeric', 'min:0.001'],
            'estado'        => ['required', 'in:pendiente,fabricada'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $datosValidados['usuario_id'] = $request->usuario_id;

        $registro = $this->repositorio->crear($pieza, $datosValidados);

        return response()->json([
            'mensaje'  => 'Registro de fabricación creado correctamente.',
            'registro' => $registro->load('pieza'),
        ], 201);
    }

    public function show(Pieza $pieza, RegistroFabricacion $registro)
    {
        abort_if(
            $registro->pieza_id !== $pieza->id,
            404,
            'Registro no encontrado para esta pieza.'
        );

        return response()->json(
            $registro->load('pieza.bloque.proyecto')
        );
    }

    public function update(Request $request, Pieza $pieza, RegistroFabricacion $registro)
    {
        abort_if(
            $registro->pieza_id !== $pieza->id,
            404,
            'Registro no encontrado para esta pieza.'
        );

        $datosValidados = $request->validate([
            'peso_real'     => ['sometimes', 'numeric', 'min:0.001'],
            'estado'        => ['sometimes', 'in:pendiente,fabricada'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $registroActualizado = $this->repositorio->actualizar($registro, $datosValidados);

        return response()->json([
            'mensaje'  => 'Registro actualizado correctamente.',
            'registro' => $registroActualizado,
        ]);
    }
}
