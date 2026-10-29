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

        if (!$fkom) {
            $this->command->error('FKOM department not found. Run PtjDepartmentSeeder first.');
            return;
        }

        User::updateOrCreate(
            ['staff_id' => 'ADM001'],
            [
                'ptj_id' => null,
                'user_name' => 'System Administrator',
                'email' => 'admin@ucpms.test',
                'user_phone_number' => '0120000001',
                'role' => 'admin',
                'password' => Hash::make('Password123!'),
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['staff_id' => 'BEN001'],
            [
                'ptj_id' => null,
                'user_name' => 'Bendahari Officer',
                'email' => 'bendahari@ucpms.test',
                'user_phone_number' => '0120000002',
                'role' => 'bendahari',
                'password' => Hash::make('Password123!'),
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['staff_id' => 'KJ001'],
            [
                'ptj_id' => $fkom->ptj_id,
                'user_name' => 'KJ FKOM',
                'email' => 'kj.fkom@ucpms.test',
                'user_phone_number' => '0120000003',
                'role' => 'kj',
                'password' => Hash::make('Password123!'),
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['staff_id' => 'PTJ001'],
            [
                'ptj_id' => $fkom->ptj_id,
                'user_name' => 'PTJ FKOM Staff',
                'email' => 'ptj.fkom@ucpms.test',
                'user_phone_number' => '0120000004',
                'role' => 'ptj',
                'password' => Hash::make('Password123!'),
                'is_active' => true,
            ]
        );

        $this->command->info('UCPMS test users created/updated successfully.');
    }
}