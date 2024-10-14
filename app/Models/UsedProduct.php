<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsedProduct extends Model
{
    public $timestamps = false;

    protected $table = 'tbl_used_products';
    protected $primaryKey = 'id_up';
    protected $fillable = ['pro_id', 'up_amount', 'wo_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'pro_id', 'pro_id');
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'wo_id', 'wo_id');
    }
}
