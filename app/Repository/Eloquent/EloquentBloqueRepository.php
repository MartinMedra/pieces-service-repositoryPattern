<?php

namespace App\Repositories\Eloquent;

use App\Models\Bloque;
use App\Models\Proyecto;
use App\Repositories\Contracts\BloqueRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentBloqueRepository implements BloqueRepositoryInterface
{
    public function obtenerPorProyecto(Proyecto $proyecto, array $filtros, int $porPagina): LengthAwarePaginator
    {
        $consulta = $proyecto->bloques()->withCount('piezas');

        if (!empty($filtros['buscar'])) {
            $consulta->where('nombre', 'ilike', "%{$filtros['buscar']}%");
        }

        return $consulta->paginate($porPagina);
    }

    public function crear(Proyecto $proyecto, array $datos): Bloque
    {
        return $proyecto->bloques()->create($datos);
    }

    public function actualizar(Bloque $bloque, array $datos): Bloque
    {
        $bloque->update($datos);
        return $bloque->fresh();
    }

    public function eliminar(Bloque $bloque): void
    {
        $bloque->delete();
    }
}
