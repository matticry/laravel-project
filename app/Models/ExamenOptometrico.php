<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamenOptometrico extends Model
{
    protected $table = 'tbl_examen_optometrico';
    protected $primaryKey = 'id_examen_optometrico';

    protected $fillable = [
        'id_historial_clinico',
        'fecha_examen',
        'queratometria_od',
        'queratometria_oi',
        'retinoscopia_od',
        'retinoscopia_oi',
        'subjetivo_od',
        'subjetivo_oi',
        'refraccion_comp_od',
        'refraccion_comp_oi',
        'percepcion_simultanea',
        'fusion',
        'estereopsis',
        'punto_proximo_conv',
        'cover_test_od',
        'cover_test_oi',
        'vision_colores_od',
        'vision_colores_oi',
        'otros',
        'observacion',
        'notas',
    ];

    protected $casts = [
        'fecha_examen' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function historialClinico(): BelongsTo
    {
        return $this->belongsTo(HistorialClinico::class, 'id_historial_clinico', 'id_historial_clinico');
    }
}
