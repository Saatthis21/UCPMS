<?php

namespace Database\Seeders;

use App\Models\PtjBudget;
use App\Models\PtjDepartment;
use Illuminate\Database\Seeder;

class PtjBudgetSeeder extends Seeder
{
    public function run(): void
    {
        $departments = PtjDepartment::all();

        foreach ($departments as $department) {
            PtjBudget::create([
                'ptj_id' => $department->ptj_id,
                'budget_year' => 2026,
                'allocated_amount' => 300000.00,
                'used_amount' => 0.00,
                'reserved_amount' => 0.00,
            ]);
        }
    }
}