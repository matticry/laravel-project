<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tbl_task';
    protected $primaryKey = 'id_task';
    public $timestamps = false;

    protected $fillable = [
        'id_service',
        'name_task',
        'status_task'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_serv');
    }
}
