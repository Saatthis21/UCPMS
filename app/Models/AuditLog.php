<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $primaryKey = 'audit_log_id';

    const UPDATED_AT = null;

    protected $fillable = [
        'procurement_request_id',
        'user_id',
        'action',
        'description',
    ];

    public function procurementRequest(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementRequest::class,
            'procurement_request_id',
            'procurement_request_id'
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}