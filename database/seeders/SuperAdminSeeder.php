<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('wellness_users')->updateOrInsert(
            ['email' => 'admin1@lifehealerkavita.com'],
            [
                'user_name'  => 'SuperAdmin1',
                'email'      => 'admin1@lifehealerkavita.com',
                'password'   => Hash::make('SuperAdminPass123!'),
                'user_type'  => 1, // 1 = Supper-Admin
                'type'       => 'Supper-Admin',
                'added_on'   => $now,
                'status'     => 1,
                'deleted'    => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('wellness_users')->updateOrInsert(
            ['email' => 'admin2@lifehealerkavita.com'],
            [
                'user_name'  => 'SuperAdmin2',
                'email'      => 'admin2@lifehealerkavita.com',
                'password'   => Hash::make('SuperAdminPass123!'),
                'user_type'  => 1, // 1 = Supper-Admin
                'type'       => 'Supper-Admin',
                'added_on'   => $now,
                'status'     => 1,
                'deleted'    => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }
}
