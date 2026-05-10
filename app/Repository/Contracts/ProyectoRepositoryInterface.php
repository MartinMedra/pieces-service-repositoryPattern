<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Proyecto;

interface ProyectoRepositoryInterface
{
    public function obtenerTodos(array $filtros, int $porPagina): LengthAwarePaginator;
    public function buscarPorId(int $id): Proyecto;
    public function crear(array $datos): Proyecto;
    public function actualizar(Proyecto $proyecto, array $datos): Proyecto;
    public function eliminar(Proyecto $proyecto): void;
}
