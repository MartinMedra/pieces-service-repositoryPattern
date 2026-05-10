<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\RegistroFabricacion;


class ReporteController extends Controller
{
    public function piezasPendientesPorProyecto()
    {
        $reporte = DB::table('registros_fabricacion as rf')
            ->join('piezas as p',    'p.id',  '=', 'rf.pieza_id')
            ->join('bloques as b',   'b.id',  '=', 'p.bloque_id')
            ->join('proyectos as pr','pr.id', '=', 'b.proyecto_id')
            ->where('rf.estado', 'pendiente')
            ->whereNull('p.deleted_at')
            ->whereNull('b.deleted_at')
            ->whereNull('pr.deleted_at')
            ->select(
                'pr.id as proyecto_id',
                'pr.nombre as proyecto',
                'pr.codigo_proyecto',
                DB::raw('COUNT(rf.id) as total_pendientes'),
                DB::raw('SUM(p.peso_teorico) as peso_total_teorico')
            )
            ->groupBy('pr.id', 'pr.nombre', 'pr.codigo_proyecto')
            ->orderByDesc('total_pendientes')
            ->get();

        return response()->json([
            'reporte'      => 'Piezas pendientes por proyecto',
            'generado_en'  => now()->toDateTimeString(),
            'datos'        => $reporte,
        ]);
    }

    public function totalesPorEstado()
    {
        $totales = RegistroFabricacion::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->get();

        $totalGeneral = $totales->sum('total');

        $datos = $totales->map(function ($fila) use ($totalGeneral) {
            return [
                'estado'     => $fila->estado,
                'total'      => $fila->total,
                'porcentaje' => $totalGeneral > 0
                    ? round(($fila->total / $totalGeneral) * 100, 2)
                    : 0,
            ];
        });

        return response()->json([
            'reporte'       => 'Totales por estado',
            'generado_en'   => now()->toDateTimeString(),
            'total_general' => $totalGeneral,
            'datos'         => $datos,
        ]);
    }
}
