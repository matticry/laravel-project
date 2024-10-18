<?php

namespace App\Models;

use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'tbl_user';
    protected $primaryKey = 'us_id';


    protected $fillable = [
        'us_name', 'us_lastName', 'us_image', 'us_address', 'us_dni',
        'us_first_phone', 'us_second_phone', 'us_email', 'us_password', 'us_status','google_id', 'email_verfied_at'
    ];

    protected $hidden = ['us_password'];

    protected $casts = [
        'email_verified_at' => 'boolean',
        'google_id' => 'string'
    ];

    public function getAuthPassword()
    {
        return $this->us_password;
    }
    public function username(): string
    {
        return 'us_dni';
    }
    public function hasPermission($permissionName): bool
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('perm_name', $permissionName)) {
                return true;
            }
        }
        return false;
    }
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'tbl_rol_user', 'us_id', 'role_id');
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
    public function getEmailForPasswordReset()
    {
        return $this->us_email;
    }

    public function tokens(): HasMany
    {
        return $this->hasMany(Token::class,'usu_id', 'us_id');

    }

}
