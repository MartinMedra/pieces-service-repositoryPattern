<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Proyecto;
use App\Models\Pieza;

class Bloque extends Model
{
    use SoftDeletes;

    protected $table = 'bloques';

    protected $fillable = [
        'proyecto_id',
        'nombre',
        'descripcion',
        'codigo_bloque',
    ];

    public function proyecto(): BelongsTo
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function piezas(): HasMany
    {
        return $this->hasMany(Pieza::class, 'bloque_id');
    }
}
