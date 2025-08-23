<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin
        $adminRole = Role::updateOrCreate(
            [
                'name' => 'admin'
            ],
            []
        );
        $admin = User::updateOrCreate(
            [
                'email' => 'admin@admin.com'
            ],
            [
                'name' => 'admin',
                'phone_number' => '01025255',
                'password' => Hash::make('1234'),
            ]
        );
        $admin->assignRole($adminRole);
    }
}
