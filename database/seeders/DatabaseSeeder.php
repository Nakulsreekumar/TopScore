<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
{
    // Create the ONE Admin
    \App\Models\User::create([
        'name' => 'admin',
        'email' => 'admin@topscore.com',
        'password' => \Illuminate\Support\Facades\Hash::make('admin123'), // Default password
        'role' => 'admin'
    ]);
}
}
