<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $primaryKey = 'vendor_id';


    protected $fillable = [
        'company_name',
        'ssm_number',
        'mof_number',
        'bank_account',
        'vendor_rating',
    ];


    protected $casts = [
        'vendor_rating' => 'decimal:2',
    ];


    /**
     * A vendor can submit quotations for many procurement requests.
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(
            VendorQuotation::class,
            'vendor_id',
            'vendor_id'
        );
    }
}