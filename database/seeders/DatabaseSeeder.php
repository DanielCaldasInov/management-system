<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('articles');
        Storage::disk('public')->makeDirectory('articles');

        User::factory()->create([
            'name' => 'Daniel Silva',
            'email' => 'daniel@example.com',
            'password' => Hash::make('password'),
        ]);

        Entity::factory(30)->create();

        Article::factory(35)->create();
    }
}
