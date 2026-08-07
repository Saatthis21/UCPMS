<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiRecommendation extends Model
{
    protected $primaryKey = 'ai_recommendation_id';

    protected $fillable = [
        'procurement_request_id',
        'vendor_id',
        'justification_text',
        'api_status',
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