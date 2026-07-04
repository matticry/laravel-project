<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionOftalmologica extends Model
{
    protected $table = 'tbl_evaluacion_oftalmologica';
    protected $primaryKey = 'id_evaluacion_oftalmologica';

    protected $fillable = [
        'id_historial_clinico',
        'fecha_examen',
        'biomicroscopia_od',
        'biomicroscopia_oi',
        'tension_od',
        'tension_oi',
        'pupilas_od',
        'pupilas_oi',
        'oftalmoscopia_od',
        'oftalmoscopia_oi',
        'schirmer_od',
        'schirmer_oi',
        'amsler_od',
        'amsler_oi',
        'otros',
        'observaciones',
    ];

    protected $casts = [
        'fecha_examen' => 'datetime',
        'tension_od'   => 'decimal:2',
        'tension_oi'   => 'decimal:2',
        'schirmer_od'  => 'decimal:2',
        'schirmer_oi'  => 'decimal:2',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function historialClinico(): BelongsTo
    {
        return $this->belongsTo(HistorialClinico::class, 'id_historial_clinico', 'id_historial_clinico');
    }
}
