<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorQuotation extends Model
{
    protected $primaryKey = 'quotation_id';

    protected $fillable = [
        'procurement_request_id',
        'vendor_id',
        'total_amount',
        'quotation_file_path',
        'warranty_period',
        'delivery_lead_time',
        'payment_terms',
    ];

    protected $casts = [
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
}