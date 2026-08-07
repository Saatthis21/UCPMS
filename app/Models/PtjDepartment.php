<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PtjDepartment extends Model
{
    protected $primaryKey = 'ptj_id';

    protected $fillable = [
        'department_name',
        'department_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'ptj_id', 'ptj_id');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(PtjBudget::class, 'ptj_id', 'ptj_id');
    }

    public function procurementRequests(): HasMany
    {
        return $this->hasMany(ProcurementRequest::class, 'ptj_id', 'ptj_id');
    }

}