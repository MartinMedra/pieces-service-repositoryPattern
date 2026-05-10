<?php

namespace App\Repositories\Eloquent;

use App\Models\Bloque;
use App\Models\Pieza;
use App\Repositories\Contracts\PiezaRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PiezaRepository implements PiezaRepositoryInterface
{
    public function obtenerPorBloque(Bloque $bloque, array $filtros, int $porPagina): LengthAwarePaginator
    {
        $consulta = $bloque->piezas()->with('ultimoRegistro');

        if (!empty($filtros['buscar'])) {
            $consulta->where('nombre', 'ilike', "%{$filtros['buscar']}%");
        }

        return $consulta->paginate($porPagina);
    }

    public function crear(Bloque $bloque, array $datos): Pieza
    {
        return $bloque->piezas()->create($datos);
    }

    public function actualizar(Pieza $pieza, array $datos): Pieza
    {
        $pieza->update($datos);
        return $pieza->fresh();
    }

    public function eliminar(Pieza $pieza): void
    {
        $pieza->delete();
    }
}
