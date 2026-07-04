<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class HistorialClinico extends Model
{
    protected $table = 'tbl_historial_clinico';
    protected $primaryKey = 'id_historial_clinico';

    protected $fillable = [
        'antecedentes_patologicos_familiares',
        'antecedentes_patologicos_personales',
        'antecedentes_oculares_familiares',
        'antecedentes_oculares_personales',
        'utiliza_lentes',
        'tipo_lente',
        'fecha_inicio_uso_lentes',
        'motivo_consulta',
        'observaciones',
    ];

    protected $casts = [
        'utiliza_lentes'         => 'boolean',
        'fecha_inicio_uso_lentes' => 'date',
        'created_at'             => 'datetime',
        'updated_at'             => 'datetime',
    ];

    // ─── Relaciones ───────────────────────────────────────────

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class, 'id_historial_clinico', 'id_historial_clinico');
    }

    public function contactologia(): HasOne
    {
        return $this->hasOne(Contactologia::class, 'id_historial_clinico', 'id_historial_clinico');
    }

    public function evaluacionOftalmologica(): HasOne
    {
        return $this->hasOne(EvaluacionOftalmologica::class, 'id_historial_clinico', 'id_historial_clinico');
    }

    public function examenOptometrico(): HasOne
    {
        return $this->hasOne(ExamenOptometrico::class, 'id_historial_clinico', 'id_historial_clinico');
    }

    public function examenesPreliminares(): HasOne
    {
        return $this->hasOne(ExamenesPreliminares::class, 'id_historial_clinico', 'id_historial_clinico');
    }

    public function lensometria(): HasOne
    {
        return $this->hasOne(Lensometria::class, 'id_historial_clinico', 'id_historial_clinico');
    }

    public function rxFinalLente(): HasOne
    {
        return $this->hasOne(RxFinalLente::class, 'id_historial_clinico', 'id_historial_clinico');
    }
}
