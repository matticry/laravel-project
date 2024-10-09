<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $table = 'tbl_employee';
    protected $primaryKey = 'id_emplo';
    public $timestamps = false;

    protected $fillable = [
        'name_emplo',
        'last_name_emplo',
        'dni_emplo',
        'id_provincia',
        'birthdate_emplo',
        'email_emplo',
        'description_emplo',
        'image_emplo',
        'status_emplo',
    ];

    protected $casts = [
        'birthdate_emplo' => 'datetime',
        'created_at_emplo' => 'datetime',
    ];

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'id_provincia');
    }
}
