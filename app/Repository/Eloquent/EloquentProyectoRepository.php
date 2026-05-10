<?php

namespace App\Repositories\Eloquent;

use App\Models\Proyecto;
use App\Repositories\Contracts\ProyectoRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentProyectoRepository implements ProyectoRepositoryInterface
{
    public function __construct(private readonly Proyecto $modelo) {}

    public function obtenerTodos(array $filtros, int $porPagina): LengthAwarePaginator
    {
        $consulta = $this->modelo->query();

        if (!empty($filtros['estado'])) {
            $consulta->where('estado', $filtros['estado']);
        }

        if (!empty($filtros['buscar'])) {
            $consulta->where(function ($q) use ($filtros) {
                $q->where('nombre', 'ilike', "%{$filtros['buscar']}%")
                  ->orWhere('codigo_proyecto', 'ilike', "%{$filtros['buscar']}%");
            });
        }

        return $consulta->withCount('bloques')->paginate($porPagina);
    }

    public function buscarPorId(int $id): Proyecto
    {
        return $this->modelo->with('bloques.piezas')->findOrFail($id);
    }

    public function crear(array $datos): Proyecto
    {
        return $this->modelo->create($datos);
    }

    public function actualizar(Proyecto $proyecto, array $datos): Proyecto
    {
        $proyecto->update($datos);
        return $proyecto->fresh();
    }

    public function eliminar(Proyecto $proyecto): void
    {
        $proyecto->delete();
    }
}
