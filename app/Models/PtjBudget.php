<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PtjBudget extends Model
{
    protected $primaryKey = 'budget_id';

    protected $fillable = [
        'ptj_id',
        'budget_year',
        'allocated_amount',
        'used_amount',
        'reserved_amount',
    ];

    protected $casts = [
        'allocated_amount' => 'decimal:2',
        'used_amount' => 'decimal:2',
        'reserved_amount' => 'decimal:2',
    ];

    public function ptjDepartment(): BelongsTo
    {
        return $this->belongsTo(PtjDepartment::class, 'ptj_id', 'ptj_id');
    }
}