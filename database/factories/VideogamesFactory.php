<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Videogames>
 */
class VideogamesFactory extends Factory
{
    
    public function definition(): array
    {
        return [
            'title' => $this->faker->name,
            'year' => $this->faker->year,
            'img_url' => $this->faker->imageUrl,
            'genre' => $this->faker->randomElement(['Action', 'Adventure', 'RPG', 'FPS', 'Platformer']),
            'is_active' => $this->faker->boolean,
            //
        ];
    }
}
