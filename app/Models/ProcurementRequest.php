<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProcurementRequest extends Model
{
    protected $primaryKey = 'procurement_request_id';

    protected $fillable = [
        'ptj_id',
        'user_id',
        'request_title',
        'request_description',
        'estimated_amount',
        'selected_quotation_id',
        'approved_amount',
        'request_status',
    ];

    protected $casts = [
        'estimated_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
    ];

    public function ptjDepartment(): BelongsTo
    {
        return $this->belongsTo(PtjDepartment::class, 'ptj_id', 'ptj_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RequestItem::class, 'procurement_request_id', 'procurement_request_id');
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(VendorQuotation::class, 'procurement_request_id', 'procurement_request_id');
    }

    public function aiRecommendation(): HasOne
    {
        return $this->hasOne(AiRecommendation::class, 'procurement_request_id', 'procurement_request_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class, 'procurement_request_id', 'procurement_request_id');
    }

    public function localOrder(): HasOne
    {
        return $this->hasOne(LocalOrder::class, 'procurement_request_id', 'procurement_request_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'procurement_request_id', 'procurement_request_id');
    }

    public function selectedQuotation(): BelongsTo
    {
        return $this->belongsTo(VendorQuotation::class, 'selected_quotation_id', 'quotation_id');
    }
}