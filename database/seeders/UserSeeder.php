<?php

namespace Database\Seeders;

use App\Models\PtjDepartment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $fkom = PtjDepartment::where('department_code', 'FKOM')->first();
        $bendahari = PtjDepartment::where('department_code', 'BENDAHARI')->first();

        User::create([
            'ptj_id' => null,
            'staff_id' => 'ADM001',
            'user_name' => 'System Administrator',
            'email' => 'admin@ucpms.test',
            'user_phone_number' => '0120000001',
            'role' => 'admin',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        User::create([
            'ptj_id' => null,
            'staff_id' => 'BEN001',
            'user_name' => 'Bendahari Officer',
            'email' => 'bendahari@ucpms.test',
            'user_phone_number' => '0120000002',
            'role' => 'bendahari',
            'password' => Hash::make('Password123!'),
            'is_active' => true,
]);

        User::create([
            'ptj_id' => $fkom->ptj_id,
            'staff_id' => 'KJ001',
            'user_name' => 'KJ FKOM',
            'email' => 'kj.fkom@ucpms.test',
            'user_phone_number' => '0120000003',
            'role' => 'kj',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        User::create([
            'ptj_id' => $fkom->ptj_id,
            'staff_id' => 'PTJ001',
            'user_name' => 'PTJ FKOM Staff',
            'email' => 'ptj.fkom@ucpms.test',
            'user_phone_number' => '0120000004',
            'role' => 'ptj',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
    }
}