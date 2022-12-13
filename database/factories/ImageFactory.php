<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
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
            'title' => $this->faker->text($maxNbChars),
            'img_url' => $this->faker->imageUrl(),
            'gallery_id' => $this->faker->numberBetween(1,10)
        ];
    }
}
