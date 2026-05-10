<?php

namespace App\Repositories\Eloquent;

use App\Models\Pieza;
use App\Models\RegistroFabricacion;
use App\Repositories\Contracts\RegistroFabricacionRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentRegistroFabricacionRepository implements RegistroFabricacionRepositoryInterface
{
    public function obtenerPorPieza(Pieza $pieza, array $filtros, int $porPagina): LengthAwarePaginator
    {
        $consulta = $pieza->registrosFabricacion();

        if (!empty($filtros['estado'])) {
            $consulta->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['fecha_desde'])) {
            $consulta->whereDate('fecha_fabricacion', '>=', $filtros['fecha_desde']);
        }

        if (!empty($filtros['fecha_hasta'])) {
            $consulta->whereDate('fecha_fabricacion', '<=', $filtros['fecha_hasta']);
        }

        return $consulta->latest('fecha_fabricacion')->paginate($porPagina);
    }

    public function crear(Pieza $pieza, array $datos): RegistroFabricacion
    {
        return $pieza->registrosFabricacion()->create([
            'peso_teorico'  => $pieza->peso_teorico,
            'peso_real'     => $datos['peso_real'],
            'estado'        => $datos['estado'],
            'observaciones' => $datos['observaciones'] ?? null,
            'usuario_id'    => $datos['usuario_id'],
        ]);
    }

    public function actualizar(RegistroFabricacion $registro, array $datos): RegistroFabricacion
    {
        $registro->update($datos);
        return $registro->fresh();
    }
}
