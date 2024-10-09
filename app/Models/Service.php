<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    public $timestamps = false;

    protected $table = 'tbl_service';

    protected $primaryKey = 'id_serv';

    protected $fillable = [ 'name_serv', 'description_serv', 'price_serv', 'status_serv'];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'id_service', 'id_serv');
    }
}
