<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RxFinalLente extends Model
{
    protected $table = 'tbl_rx_final_lente';
    protected $primaryKey = 'id_rx_final_lente';

    protected $fillable = [
        'id_historial_clinico',
        'fecha_examen',
        'od_esf',
        'od_cyl',
        'od_eje',
        'od_add',
        'oi_esf',
        'oi_cyl',
        'oi_eje',
        'oi_add',
        'od_prisma',
        'od_dnp',
        'od_dp',
        'od_alt',
        'od_av_lejos',
        'od_av_cerca',
        'oi_prisma',
        'oi_dnp',
        'oi_dp',
        'oi_alt',
        'oi_av_lejos',
        'oi_av_cerca',
        'avcc_ao_lejos',
        'avcc_ao_cerca',
        'diseno_lente',
        'tratamiento',
        'material',
        'diagnostico',
        'recomendaciones',
        'observaciones',
    ];

    protected $casts = [
        'fecha_examen'  => 'datetime',
        'od_esf'        => 'decimal:2',
        'od_cyl'        => 'decimal:2',
        'od_eje'        => 'integer',
        'od_add'        => 'decimal:2',
        'oi_esf'        => 'decimal:2',
        'oi_cyl'        => 'decimal:2',
        'oi_eje'        => 'integer',
        'oi_add'        => 'decimal:2',
        'od_prisma'     => 'decimal:2',
        'od_dnp'        => 'decimal:2',
        'od_dp'         => 'decimal:2',
        'od_alt'        => 'decimal:2',
        'oi_prisma'     => 'decimal:2',
        'oi_dnp'        => 'decimal:2',
        'oi_dp'         => 'decimal:2',
        'oi_alt'        => 'decimal:2',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function historialClinico(): BelongsTo
    {
        return $this->belongsTo(HistorialClinico::class, 'id_historial_clinico', 'id_historial_clinico');
    }
}
