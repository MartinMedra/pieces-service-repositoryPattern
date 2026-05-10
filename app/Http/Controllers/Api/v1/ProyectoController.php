<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Proyecto;
use App\Repositories\Contracts\ProyectoRepositoryInterface;
use Illuminate\Http\Request;

class ProyectoController extends Controller
{
    public function __construct(
        private readonly ProyectoRepositoryInterface $repositorio
    ) {}

    public function index(Request $request)
    {
        $proyectos = $this->repositorio->obtenerTodos(
            filtros:   $request->only(['estado', 'buscar']),
            porPagina: (int) $request->input('por_pagina', 15)
        );

        return response()->json($proyectos);
    }

    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'nombre'          => ['required', 'string', 'max:255'],
            'descripcion'     => ['nullable', 'string'],
            'codigo_proyecto' => ['required', 'string', 'unique:proyectos,codigo_proyecto'],
            'estado'          => ['sometimes', 'in:activo,inactivo'],
        ]);

        $proyecto = $this->repositorio->crear($datosValidados);

        return response()->json([
            'mensaje'  => 'Proyecto creado correctamente.',
            'proyecto' => $proyecto,
        ], 201);
    }

    public function show(Proyecto $proyecto)
    {
        return response()->json(
            $this->repositorio->buscarPorId($proyecto->id)
        );
    }

    public function update(Request $request, Proyecto $proyecto)
    {
        $datosValidados = $request->validate([
            'nombre'          => ['sometimes', 'string', 'max:255'],
            'descripcion'     => ['nullable', 'string'],
            'codigo_proyecto' => ['sometimes', 'string', 'unique:proyectos,codigo_proyecto,' . $proyecto->id],
            'estado'          => ['sometimes', 'in:activo,inactivo'],
        ]);

        $proyectoActualizado = $this->repositorio->actualizar($proyecto, $datosValidados);

        return response()->json([
            'mensaje'  => 'Proyecto actualizado correctamente.',
            'proyecto' => $proyectoActualizado,
        ]);
    }

    public function destroy(Proyecto $proyecto)
    {
        $this->repositorio->eliminar($proyecto);

        return response()->json([
            'mensaje' => 'Proyecto eliminado correctamente.',
        ]);
    }
}
