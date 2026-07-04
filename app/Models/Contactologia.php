<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contactologia extends Model
{
    protected $table = 'tbl_contactologia';
    protected $primaryKey = 'id_contactologia';

    protected $fillable = [
        'id_historial_clinico',
        'fecha_examen',
        'od_esf',
        'od_cyl',
        'od_eje',
        'od_diametro',
        'od_curva_base',
        'oi_esf',
        'oi_cyl',
        'oi_eje',
        'oi_diametro',
        'oi_curva_base',
        'od_av',
        'oi_av',
        'avcc_ao_lejos',
        'avcc_ao_cerca',
        'tipo_lente',
        'observacion',
    ];

    protected $casts = [
        'fecha_examen'   => 'datetime',
        'od_esf'         => 'decimal:2',
        'od_cyl'         => 'decimal:2',
        'od_eje'         => 'integer',
        'od_diametro'    => 'decimal:2',
        'od_curva_base'  => 'decimal:2',
        'oi_esf'         => 'decimal:2',
        'oi_cyl'         => 'decimal:2',
        'oi_eje'         => 'integer',
        'oi_diametro'    => 'decimal:2',
        'oi_curva_base'  => 'decimal:2',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function historialClinico(): BelongsTo
    {
        return $this->belongsTo(HistorialClinico::class, 'id_historial_clinico', 'id_historial_clinico');
    }
}
