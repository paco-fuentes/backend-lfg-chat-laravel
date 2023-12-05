<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Videogames>
 */
class VideogamesFactory extends Factory
{
    
    public function definition(): array
    {
        return [
            'title' => $this->faker->title,
            'year' => $this->faker->year,
            'img_url' => $this->faker->imageUrl,
            'genre' => $this->faker->randomElement(['MMORPG', 'Action', 'Adventure', 'RPG', 'FPS']),
            'is_active' => $this->faker->boolean,
            //
        ];
    }
}
