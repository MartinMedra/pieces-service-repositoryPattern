<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Pieza;

class RegistroFabricacion extends Model
{
    protected $table = 'registros_fabricacion';

    protected $fillable= [
        'pieza_id',
        'fecha_fabricacion',
        'peso_teorico',
        'peso_real',
        'estado',
        'observaciones',
        'usuario_id',
    ];

    protected $cast = [
        'fecha_fabricacion' => 'datetime',
        'peso_teorico'      => 'decimal:3',
        'peso_real'         => 'decimal:3',
        'diferencia_peso'   => 'decimal:3',
    ];

    protected $guarded = ['diferencia_peso']; //esta me la trae ya calculada la base de datos

    public function pieza(): BelongsTo
    {
        return $this->belongsTo(Pieza::class, 'pieza_id');
    }
}
