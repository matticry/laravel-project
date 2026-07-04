<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lensometria extends Model
{
    protected $table = 'tbl_lensometria';
    protected $primaryKey = 'id_lensometria';

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
        'oi_prisma',
        'oi_dnp',
        'oi_dp',
        'oi_alt',
        'diseno_lente',
        'material',
        'tratamiento',
    ];

    protected $casts = [
        'fecha_examen' => 'datetime',
        'od_esf'       => 'decimal:2',
        'od_cyl'       => 'decimal:2',
        'od_eje'       => 'integer',
        'od_add'       => 'decimal:2',
        'oi_esf'       => 'decimal:2',
        'oi_cyl'       => 'decimal:2',
        'oi_eje'       => 'integer',
        'oi_add'       => 'decimal:2',
        'od_prisma'    => 'decimal:2',
        'od_dnp'       => 'decimal:2',
        'od_dp'        => 'decimal:2',
        'od_alt'       => 'decimal:2',
        'oi_prisma'    => 'decimal:2',
        'oi_dnp'       => 'decimal:2',
        'oi_dp'        => 'decimal:2',
        'oi_alt'       => 'decimal:2',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function historialClinico(): BelongsTo
    {
        return $this->belongsTo(HistorialClinico::class, 'id_historial_clinico', 'id_historial_clinico');
    }
}
