<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Token extends Model
{
    public $timestamps = false;

    protected $table = 'tbl_token';
    protected $primaryKey = 'token_id';

    protected $fillable = [
        'usu_id',
        'token',
        'created_at',
        'expires_at',
        'is_revoked',
    ];

    protected $casts = [
        'created_at' => 'timestamp',
        'expires_at' => 'timestamp',
        'is_revoked' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usu_id', 'us_id');
    }

}
