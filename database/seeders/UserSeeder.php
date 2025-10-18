<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $headAdmin = User::create([
            'name' => 'Head Admin',
            'email' => 'head@vodeco.co.id',
            'password' => Hash::make('password'),
        ]);
        $headAdmin->assignRole('Head Admin');

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@vodeco.co.id',
            'password' => Hash::make('password'),
        ]);
        $superAdmin->assignRole('Super Admin');

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@vodeco.co.id',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('Admin');
    }
}
