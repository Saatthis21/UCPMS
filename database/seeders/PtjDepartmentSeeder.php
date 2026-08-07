<?php

namespace Database\Seeders;

use App\Models\PtjDepartment;
use Illuminate\Database\Seeder;

class PtjDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        PtjDepartment::create([
            'department_name' => 'Faculty of Computing',
            'department_code' => 'FKOM',
            'is_active' => true,
        ]);

        PtjDepartment::create([
            'department_name' => 'PTJ Demo 2',
            'department_code' => 'PTJ002',
            'is_active' => true,
        ]);

        PtjDepartment::create([
            'department_name' => 'PTJ Demo 3',
            'department_code' => 'PTJ003',
            'is_active' => true,
        ]);

        PtjDepartment::create([
            'department_name' => 'PTJ Demo 4',
            'department_code' => 'PTJ004',
            'is_active' => true,
        ]);

        PtjDepartment::create([
            'department_name' => 'PTJ Demo 5',
            'department_code' => 'PTJ005',
            'is_active' => true,
        ]);
    }
}