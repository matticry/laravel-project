<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class WorkOrder extends Model
{
    protected $table = 'tbl_work_order';
    protected $primaryKey = 'wo_id';
    public $timestamps = false;
    protected $fillable = [
        'wo_start_date', 'wo_final_date', 'wo_status', 'wo_description',
        'wo_total', 'us_id', 'wo_order_code', 'cli_id', 'pdf_report'
    ];
    protected $casts = [
        'wo_total' => 'float',
        'wo_status' => 'string',
    ];

    protected $dates = ['wo_start_date', 'wo_final_date'];

    public function details(): HasMany
    {
        return $this->hasMany(DetailWorkOrder::class, 'wo_id', 'wo_order_code');
    }

    public function services(): HasMany
    {
        return $this->hasMany(WorkOrderService::class,  'wo_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(WorkOrderTask::class, 'wo_id');
    }
    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cli_id', 'us_id');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'us_id', 'us_id');
    }
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, DetailWorkOrder::class, 'wo_id', 'pro_id', 'wo_id', 'pro_id');
    }



}
