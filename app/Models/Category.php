<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'tbl_category';
    protected $primaryKey = 'cat_id';

    protected $fillable = ['cat_name', 'cat_status', 'cat_description'];

    public static function create(array $all)
    {
        return static::query()->create($all);
    }
}
