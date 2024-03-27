<?php

namespace Database\Factories;

use App\Models\Itinerary;
use App\Models\Destination;
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
            'itinerary_id' => Itinerary::factory(),
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
    public function configure()
    {
        return $this->afterCreating(function (Destination $destination) {
            $destination->update(['itinerary_id' => $destination->itinerary->id]);
        });
    }
}
