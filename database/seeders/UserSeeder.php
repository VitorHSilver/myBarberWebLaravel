<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Criar um admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);


        // Criar alguns profissionais
        User::create([
            'name' => 'Professional 1',
            'email' => 'pro1@example.com',
            'password' => bcrypt('password'),
            'role' => 'professional',
        ]);

        User::create([
            'name' => 'Professional 2',
            'email' => 'pro2@example.com',
            'password' => bcrypt('password'),
            'role' => 'professional',
        ]);
    }
}
