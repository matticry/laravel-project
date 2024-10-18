<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{

    public $timestamps = false;

    protected $table = 'tbl_report';

    protected $primaryKey = 'id_report';

    protected $fillable = [
        'description_report',
        'image_report',
        'image_before_report',
        'signature_client_report',
        'id_work_order',
        'pdf_report',
        'signature_report'
    ];

    protected $casts = [
        'image_report' => 'string',
        'image_before_report' => 'string',
        'signature_client_report' => 'string',
        'signature_report' => 'string',
        'pdf_report' => 'string'
    ];
    protected $dates = ['created_at'];


    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'id_work_order', 'wo_id');
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }
    public function usedProducts(): HasMany
    {
        return $this->hasMany(UsedProduct::class, 'wo_id', 'id_work_order');
    }
}
