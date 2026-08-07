<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'local_order_id',
        'user_id',
        'invoice_number',
        'invoice_date',
        'invoice_amount',
        'invoice_file_path',
        'payment_status',
        'paid_at',
        'marked_paid_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'invoice_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function localOrder(): BelongsTo
    {
        return $this->belongsTo(LocalOrder::class, 'local_order_id', 'local_order_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function markedPaidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'marked_paid_by', 'user_id');
    }
}