<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Consulta extends Model
{
    use Notifiable;

    protected $table = 'tbl_consulta';
    protected $primaryKey = 'id_co';
    public $timestamps = false;

    protected $fillable = [
        'id_paciente',
        'fecha_registro',
        'motivo',
        'id_historial_clinico',
        'estado',
    ];

    protected $attributes = [
        'estado' => 'A',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
    ];


    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'id_paciente', 'us_id');
    }

    public function historialClinico(): BelongsTo
    {
        return $this->belongsTo(HistorialClinico::class, 'id_historial_clinico');
    }
}
