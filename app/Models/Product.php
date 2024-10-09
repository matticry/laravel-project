<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Notifiable, SoftDeletes, Searchable;
    public $timestamps = false;

    protected $table = 'tbl_product';

    protected $primaryKey = 'pro_id';

    protected $fillable = [
        'pro_name', 'pro_amount','pro_unit_price', 'pro_status', 'pro_code', 'pro_description' , 'pro_image', 'cat_id',
    ];

    public function tblCategory()
    {
        return $this->belongsTo(Category::class, 'tbl_category_id', 'cat_id');
    }
}
