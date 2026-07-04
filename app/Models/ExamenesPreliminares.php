<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamenesPreliminares extends Model
{
    protected $table = 'tbl_examenes_preliminares';
    protected $primaryKey = 'id_examenes_preliminares';

    protected $fillable = [
        'id_historial_clinico',
        'fecha_examen',
        'observacion_od',
        'observacion_oi',
        'av_od',
        'av_oi',
        'av_ao',
        'av_estenop_od',
        'av_estenop_oi',
        'motilidad_1',
        'hirschberg_1',
        'purkinje',
        'ccv_od',
        'ccv_oi',
        'motilidad_2',
        'hirschberg_2',
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
