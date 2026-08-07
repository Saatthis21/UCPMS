<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LocalOrder extends Model
{
    protected $primaryKey = 'local_order_id';

    protected $fillable = [
        'procurement_request_id',
        'vendor_id',
        'lo_number',
        'lo_date',
        'total_amount',
        'lo_file_path',
        'lo_status',
    ];

    protected $casts = [
        'lo_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function procurementRequest(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementRequest::class,
            'procurement_request_id',
            'procurement_request_id'
        );
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'vendor_id');
    }

    public function deliveryOrder(): HasOne
    {
        return $this->hasOne(DeliveryOrder::class, 'local_order_id', 'local_order_id');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'local_order_id', 'local_order_id');
    }
    
}