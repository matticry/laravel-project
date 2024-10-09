<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Notifiable;
    public $timestamps = false;

    protected $table = 'tbl_product';

    protected $primaryKey = 'pro_id';

    protected $fillable = [
        'pro_name',
        'pro_amount',
        'pro_unit_price',
        'pro_status',
        'pro_description' ,
        'pro_image',
        'cat_id',
    ];
    protected $casts = [
        'pro_amount' => 'integer',
        'pro_unit_price' => 'decimal:2',
        'pro_status' => 'string',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'cat_id', 'cat_id');
    }
}
