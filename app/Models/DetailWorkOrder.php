<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailWorkOrder extends Model
{
    public $timestamps = false;

    protected $table = 'tbl_detail_work_order';
    protected $primaryKey = 'dwo_id';
    protected $fillable = ['wo_id', 'pro_id', 'dwo_amount'];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'wo_id', 'wo_order_code');
    }
}
