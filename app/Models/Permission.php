<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $table = 'tbl_permission';
    protected $primaryKey = 'perm_id';
    public $timestamps = false;

    protected $fillable = [
        'perm_name'
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'tbl_role_permission', 'perm_id', 'role_id');
    }
}
