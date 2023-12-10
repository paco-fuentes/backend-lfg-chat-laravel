<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // admin
        \App\Models\User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@admin.com',
            // 'password' => 'Password1234&',
            'password' => '$2y$12$jN3ry46XlEscFlWT9rofBuJLMaOuD.cEtq7RVIZJZunmBSAHfsv7q',
            'role' => 'admin'
        ]);

        // common users
        \App\Models\User::factory()->create([
            'username' => 'mery',
            'email' => 'mery@mery.com',
            // 'password' => 'Password1234&',
            'password' => '$2y$12$jN3ry46XlEscFlWT9rofBuJLMaOuD.cEtq7RVIZJZunmBSAHfsv7q',
        ]);

        \App\Models\User::factory()->create([
            'username' => 'christian',
            'email' => 'christian@christian.com',
            // 'password' => 'Password1234&',
            'password' => '$2y$12$jN3ry46XlEscFlWT9rofBuJLMaOuD.cEtq7RVIZJZunmBSAHfsv7q',
        ]);

        \App\Models\User::factory()->create([
            'username' => 'paco',
            'email' => 'paco@paco.com',
            // 'password' => 'Password1234&',
            'password' => '$2y$12$jN3ry46XlEscFlWT9rofBuJLMaOuD.cEtq7RVIZJZunmBSAHfsv7q',
        ]);

        // random factory users
        \App\Models\User::factory(10)->create();

        // videogames
        \App\Models\Videogames::factory()->create([
            'title' => 'Super Mario',
            'year' => '1989',
            'genre' => 'Platformer'
        ]);
        \App\Models\Videogames::factory()->create([
            'title' => 'Zelda',
            'year' => '1993',
            'genre' => 'Adventure'
        ]);
        \App\Models\Videogames::factory()->create([
            'title' => 'Sonic',
            'year' => '1991',
            'genre' => 'Platformer'
        ]);
        \App\Models\Videogames::factory()->create([
            'title' => 'Doom',
            'year' => '1993',
            'genre' => 'FPS'
        ]);
        \App\Models\Videogames::factory()->create([
            'title' => 'Doom II',
            'year' => '1995',
            'genre' => 'FPS'
        ]);
        \App\Models\Videogames::factory()->create([
            'title' => 'Fallout 3',
            'year' => '2008',
            'genre' => 'RPG'
        ]);
    }
};
