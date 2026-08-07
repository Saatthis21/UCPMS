<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestItem extends Model
{
    protected $primaryKey = 'request_item_id';

    protected $fillable = [
        'procurement_request_id',
        'item_name',
        'item_description',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function procurementRequest(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementRequest::class,
            'procurement_request_id',
            'procurement_request_id'
        );
    }
}