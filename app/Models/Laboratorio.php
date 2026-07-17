<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Laboratorio extends Model
{
    protected $table = 'tbl_laboratorio';
    protected $primaryKey = 'id_laboratorio';

    protected $fillable = [
        'nombre',
        'categoria',
        'fecha_creacion',
        'id_encargado',
    ];

    protected $casts = [
        'fecha_creacion' => 'date',
    ];

    /**
     * Encargado (usuario) del laboratorio.
     */
    public function encargado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_encargado', 'us_id');
    }
}
