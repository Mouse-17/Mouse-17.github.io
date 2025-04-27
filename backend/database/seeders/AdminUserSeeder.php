<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@keysport.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'phone' => '0123456789',
            'email_verified_at' => now(),
        ]);
    }
} 