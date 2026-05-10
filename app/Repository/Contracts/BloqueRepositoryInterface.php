<?php

namespace App\Repositories\Contracts;

use App\Models\Bloque;
use App\Models\Proyecto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface BloqueRepositoryInterface
{
    public function obtenerPorProyecto(Proyecto $proyecto, array $filtros, int $porPagina): LengthAwarePaginator;
    public function crear(Proyecto $proyecto, array $datos): Bloque;
    public function actualizar(Bloque $bloque, array $datos): Bloque;
    public function eliminar(Bloque $bloque): void;
}
