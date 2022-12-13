<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $maxNbChars = 100;
        return [
            'title' => $this->faker->randomElement(['Nature','Cars','Animals']),
            'description' => $this->faker->text($maxNbChars),
            'user_id' => $this->faker->numberBetween(1,10)
        ];
    }
}
