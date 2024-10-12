<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrderService extends Model
{
    public $timestamps = false;

    protected $table = 'tbl_work_order_service';
    protected $primaryKey = 'wo_service_id';
    protected $fillable = ['wo_id', 'service_id', 'price_service'];

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'wo_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id_serv');
    }
}
