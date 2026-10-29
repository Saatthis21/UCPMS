<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiRecommendation extends Model
{
    protected $primaryKey = 'ai_recommendation_id';

    protected $fillable = [
        'procurement_request_id',
        'recommended_quotation_id',
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

    public function recommendedQuotation(): BelongsTo
    {
        return $this->belongsTo(
            VendorQuotation::class,
            'recommended_quotation_id',
            'quotation_id'
        );
    }
}