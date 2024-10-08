<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'tbl_user';
    protected $primaryKey = 'us_id';


    protected $fillable = [
        'us_name', 'us_lastName', 'us_image', 'us_address', 'us_dni',
        'us_first_phone', 'us_second_phone', 'us_email', 'us_password', 'us_status'
    ];

    protected $hidden = ['us_password'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->us_password;
    }
    public function username(): string
    {
        return 'us_dni';
    }
}
