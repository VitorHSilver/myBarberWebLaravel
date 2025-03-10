<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory(7)->create();

        // User::factory()->create([
        //     'name' => 'Admin',
        //     'email' => 'admin@adm.com',
        //     'password' => bcrypt('123456789'),
        //     'role' => 'admin',
        // ]);
        
        User::factory()->create([
            'name' => 'Your name',
            'email' => 'your_email@test.com',
            'password' => bcrypt('Your password'),
            'role' => 'professional',
        ]);
    }
}
