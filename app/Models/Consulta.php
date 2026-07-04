<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Consulta extends Model
{
    protected $table = 'tbl_consulta';
    protected $primaryKey = 'id_co';

    protected $fillable = [
        'id_paciente',
        'fecha_registro',
        'motivo',
        'id_historial_clinico',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];

    /**
     * Relación con el paciente (Profile/Usuario)
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'id_paciente', 'us_id');
    }

    /**
     * Relación con el historial clínico
     * (ajusta el modelo HistorialClinico si ya lo tienes)
     */
    public function historialClinico(): BelongsTo
    {
        return $this->belongsTo(HistorialClinico::class, 'id_historial_clinico');
    }
}
