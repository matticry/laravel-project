<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailWorkOrder extends Model
{
    public $timestamps = false;

    protected $table = 'tbl_detail_work_order';
    protected $primaryKey = 'dwo_id';
    protected $fillable = ['wo_id', 'pro_id', 'dwo_amount','pro_code'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'wo_id', 'pro_id');
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'wo_id', 'wo_order_code');
    }
}
