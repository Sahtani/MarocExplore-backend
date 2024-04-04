<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\ItineraryController;
use App\Models\Category;
use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ItineraryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_method_returns_all_itineraries()
    {
        $this->withoutExceptionHandling(); 
    
        Itinerary::factory()->count(3)->create(['user_id' => 1]);
    
        $controller = new ItineraryController();
    
        $response = $controller->index();
    
        $this->assertCount(3, $response->getData()->itineraries);
    
        $this->assertEquals(200, $response->getStatusCode());
    }
    

   
    
    
    
}