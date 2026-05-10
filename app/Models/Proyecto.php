<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Bloque;

class Proyecto extends Model
{
    use SoftDeletes;

    protected $table = 'proyectos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'codigo_proyecto',
        'estado',
    ];

    public function bloques(): HasMany
    {
        return $this->hasMany(Bloque::class, 'proyecto_id');
    }

    public function getPiezasTotalesAttribute(): int
    {
        return $this->bloques()->withCount('piezas')->get()
                    ->sum('piezas_count');
    }
}
