<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryOrder extends Model
{
    protected $primaryKey = 'delivery_order_id';

    protected $fillable = [
        'local_order_id',
        'user_id',
        'do_number',
        'do_pdf_path',
        'delivery_date',
        'vendor_rating',
        'delivery_comments',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'vendor_rating' => 'integer',
    ];

    public function localOrder(): BelongsTo
    {
        return $this->belongsTo(LocalOrder::class, 'local_order_id', 'local_order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}