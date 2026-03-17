<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Company;
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
        Storage::disk('public')->deleteDirectory('company');

        Storage::disk('public')->makeDirectory('articles');
        Storage::disk('public')->makeDirectory('company');

        User::factory()->create([
            'name' => 'Daniel Silva',
            'email' => 'daniel@example.com',
            'password' => Hash::make('password'),
        ]);

        Company::factory()->create();

        Entity::factory(30)->create();

        Article::factory(35)->create();
    }
}
