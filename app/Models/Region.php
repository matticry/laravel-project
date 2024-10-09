<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $table = 'tbl_region';
    protected $primaryKey = 'id_region';
    public $timestamps = false;

    protected $fillable = [
        'nombre_region'
    ];

    /**
     * Get the provincias for the region.
     */
    public function provincias(): HasMany
    {
        return $this->hasMany(Provincia::class, 'id_region');
    }
}
