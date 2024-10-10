<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;

class Profile extends Model
{
    use Notifiable;

    protected $table = 'tbl_user';
    protected $primaryKey = 'us_id';

    protected $fillable = [
        'us_name', 'us_lastName', 'us_image', 'us_address', 'us_dni',
        'us_first_phone', 'us_second_phone', 'us_email', 'us_password',
        'us_status','created_at','updated_at',
    ];

    protected $casts = [
        'updated_at' => 'datetime',
        'email_verified_at' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'tbl_rol_user', 'us_id', 'role_id');
    }
    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }
}
