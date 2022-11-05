<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\User::factory()->create([
            'name' => 'Ita Purnama Sari',
            'username' => 'itns',
            'email' => 'ita@gmail.com',
            'email_verified_at' => now(),
            'phone_number' => '021828282828',
            'password' => 'admin123', // password
        ]);
        \App\Models\User::factory(10)->create();

    }
}
