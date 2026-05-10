<?php

namespace App\Repositories\Contracts;

use App\Models\Bloque;
use App\Models\Pieza;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PiezaRepositoryInterface
{
    public function obtenerPorBloque(Bloque $bloque, array $filtros, int $porPagina): LengthAwarePaginator;
    public function crear(Bloque $bloque, array $datos): Pieza;
    public function actualizar(Pieza $pieza, array $datos): Pieza;
    public function eliminar(Pieza $pieza): void;
}
