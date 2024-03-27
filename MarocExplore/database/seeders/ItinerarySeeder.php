<?php

namespace Database\Seeders;

use App\Models\Itinerary;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItinerarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Itinerary::factory()->count(6)->create();
    }
}
