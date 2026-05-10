<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\RegistroFabricacion;

class Pieza extends Model
{
    use SoftDeletes;

    protected $table = 'piezas';

    protected $fillable = [
        'bloque_id',
        'nombre',
        'codigo_pieza',
        'descripcion',
        'peso_teorico',
    ];

    protected $cast = [
        'peso_teorico' => 'decimal:3',
    ];

    public function bloque(): BelongsTo
    {
        return $this->belongsTo(Bloque::class, 'bloque_id');
    }

    public function registrosFabricacion(): HasMany
    {
        return $this->hasMany(RegistroFabricacion::class, 'pieza_id');
    }

    public function ultimoRegistro():HasMany
    {
        return $this->hasMany(RegistroFabricacion::class, 'pieza_id')
                    ->latest('fecha_fabricacion');
    }
}
