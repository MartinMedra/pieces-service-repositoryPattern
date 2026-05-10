<?php

namespace App\Repositories\Contracts;

use App\Models\Pieza;
use App\Models\RegistroFabricacion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RegistroFabricacionRepositoryInterface
{
    public function obtenerPorPieza(Pieza $pieza, array $filtros, int $porPagina): LengthAwarePaginator;
    public function crear(Pieza $pieza, array $datos): RegistroFabricacion;
    public function actualizar(RegistroFabricacion $registro, array $datos): RegistroFabricacion;
}
