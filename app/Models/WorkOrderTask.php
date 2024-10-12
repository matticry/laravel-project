<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrderTask extends Model
{
    public $timestamps = false;

    protected $table = 'tbl_work_order_task';
    protected $primaryKey = 'wo_task_id';
    protected $fillable = ['wo_id', 'task_id', 'task_status'];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class, 'wo_id');
    }


}
