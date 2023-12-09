<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        // \App\Models\Videogames::factory(10)->create();

        // admin
        \App\Models\User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password'=>'Password1234&',
            'role'=>'admin'
        ]);

        // videogames
        \App\Models\Videogames::factory()->create([
            'title' => 'Super Mario',
            'year' => '1989',
            'genre'=>'Platformer'
        ]);
        \App\Models\Videogames::factory()->create([
            'title' => 'Zelda',
            'year' => '1993',
            'genre'=>'Adventure'
        ]);
        \App\Models\Videogames::factory()->create([
            'title' => 'Sonic',
            'year' => '2000',
            'genre'=>'Platformer'
        ]);
        \App\Models\Videogames::factory()->create([
            'title' => 'Doom',
            'year' => '1993',
            'genre'=>'FPS'
        ]);
    }
};