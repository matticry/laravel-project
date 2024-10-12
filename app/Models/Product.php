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
        'pro_code'
    ];
    protected $casts = [
        'pro_amount' => 'integer',
        'pro_unit_price' => 'decimal:2',
        'pro_status' => 'string',
        'pro_description' => 'string',
        'pro_code' => 'string',
        'pro_image' => 'string'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'cat_id', 'cat_id');
    }

    public static function generateProductCode(): string
    {
        $lastProduct = self::orderBy('pro_id', 'desc')->first();

        if (!$lastProduct) {
            return 'P0001';
        }

        $lastCode = $lastProduct->pro_code;
        $numericPart = intval(substr($lastCode, 1));
        $newNumericPart = $numericPart + 1;

        return 'P' . str_pad($newNumericPart, 4, '0', STR_PAD_LEFT);
    }


}
