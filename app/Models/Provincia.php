<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'provincia';
    protected $primaryKey = 'id_provincia';
    public $timestamps = false;

    protected $fillable = [
        'nombre_provincia',
        'capital_provincia',
        'descripcion_provincia',
        'poblacion_provincia',
        'superficie_provincia',
        'latitud_provincia',
        'longitud_provincia',
        'id_region'
    ];

    protected $casts = [
        'poblacion_provincia' => 'decimal:2',
        'superficie_provincia' => 'decimal:2',
        'latitud_provincia' => 'decimal:6',
        'longitud_provincia' => 'decimal:6',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region');
    }

    public function empleados()
    {
        return $this->hasMany(Employee::class, 'id_provincia');
    }
}
