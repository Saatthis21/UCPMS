<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        Vendor::create([
            'company_name' => 'Alpha Tech Solutions Sdn. Bhd.',
            'ssm_number' => '202601000001',
            'mof_number' => 'MOF-001',
            'bank_account' => '1001001001',
            'vendor_rating' => 0.00,
        ]);

        Vendor::create([
            'company_name' => 'Global Office Supplies Sdn. Bhd.',
            'ssm_number' => '202601000002',
            'mof_number' => 'MOF-002',
            'bank_account' => '1001001002',
            'vendor_rating' => 0.00,
        ]);

        Vendor::create([
            'company_name' => 'Smart Vision Enterprise',
            'ssm_number' => '202601000003',
            'mof_number' => 'MOF-003',
            'bank_account' => '1001001003',
            'vendor_rating' => 0.00,
        ]);

        Vendor::create([
            'company_name' => 'Mega Digital Resources Sdn. Bhd.',
            'ssm_number' => '202601000004',
            'mof_number' => 'MOF-004',
            'bank_account' => '1001001004',
            'vendor_rating' => 0.00,
        ]);

        Vendor::create([
            'company_name' => 'Eastern Business Systems Sdn. Bhd.',
            'ssm_number' => '202601000005',
            'mof_number' => 'MOF-005',
            'bank_account' => '1001001005',
            'vendor_rating' => 0.00,
        ]);
    }
}