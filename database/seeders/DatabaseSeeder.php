<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Entity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Daniel Silva',
            'email' => 'daniel@example.com',
            'password' => Hash::make('password'),
        ]);

        Entity::factory(30)->create();
    }
}
