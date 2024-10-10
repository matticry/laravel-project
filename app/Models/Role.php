<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $table = 'tbl_role';
    protected $primaryKey = 'rol_id';
    public $timestamps = false;

    protected $fillable = [
        'rol_name',
        'rol_status'
    ];

    protected $attributes = [
        'rol_status' => 'A'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tbl_rol_user', 'role_id', 'us_id');
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'tbl_role_permission', 'role_id', 'perm_id');
    }
}
