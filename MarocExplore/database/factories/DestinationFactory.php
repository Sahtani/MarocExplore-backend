<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'accommodation' => $this->faker->word,
            'places' => $this->generateShortText(),
            'activities' => $this->generateShortText(),
            'dishes' => $this->generateShortText(),
        ];
    }

    /**
     * Generate short text (3 words or more)
     *
     * @return string
     */
    protected function generateShortText()
    {
        return implode(' ', $this->faker->words(3));
    }
}
