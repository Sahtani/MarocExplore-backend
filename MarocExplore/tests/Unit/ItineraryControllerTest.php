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

    // public function test_store_method_creates_itinerary()
    // {
    //     $this->withoutExceptionHandling(); 
        
    //     // Générer un utilisateur fictif avec la colonne "image" nullable
    //     $user = \App\Models\User::factory()->create();
    
    //     // Générer une catégorie fictive
    //     $category = \App\Models\Category::factory()->create();
    
    //     // Définir les données de la demande avec les données de l'utilisateur et de la catégorie
    //     $requestData = [
    //         'title' => 'Test Itinerary',
    //         'category_id' => $category->id,
    //         'duration' => '5 days',
    //         'user_id' => $user->id,
    //         'image' => 'dummy_image.jpg',
    //     ];
    
    //     // Créer une instance de Request avec les données de la demande
    //     $request = new Request($requestData);
  
    //     // Instancier le contrôleur ItineraryController
    //     $controller = new ItineraryController(); // Assurez-vous que le contrôleur est correctement importé
    
    //     // Appeler la méthode store() du contrôleur
    //     $response = $controller->store($request);
       
    //     // Vérifier si l'itinéraire a été correctement stocké dans la base de données
    //     $this->assertDatabaseHas('itineraries', $requestData);
    
    //     // Vérifier le code de statut de la réponse
    //     $this->assertEquals(201, $response->getStatusCode());
    // }

    public function test_index_method_returns_all_itineraries()
    {
        $this->withoutExceptionHandling(); 
    
        // Create itineraries associated with a fake user ID
        Itinerary::factory()->count(3)->create(['user_id' => 1]);
    
        // Instantiate the ItineraryController
        $controller = new ItineraryController();
    
        // Call the index method on the controller
        $response = $controller->index();
    
        // Assert that the response contains the correct number of itineraries
        $this->assertCount(3, $response->getData()->itineraries);
    
        // Assert the status code of the response
        $this->assertEquals(200, $response->getStatusCode());
    }
    

    public function test_filtre_method_filters_itineraries_by_category_and_duration()
    {
        $this->withoutExceptionHandling(); 
    
        // Create itineraries
        Itinerary::factory()->create(['category_id' => '1', 'duration' => '5 days']);
        Itinerary::factory()->create(['category_id' => '2', 'duration' => '10 days']);
        Itinerary::factory()->create(['category_id' => '3', 'duration' => '8 days']);
    
        // Filter itineraries by category_id
        $request = new Request(['category_id' => 1]);
        $controller = new ItineraryController();
        $response = $controller->filtre($request);
        $responseData = json_decode($response->getContent()); // Convert response data to array
    
        $this->assertCount(1, $responseData->data); // Ensure data is iterable
        $this->assertEquals('1', $responseData->data[0]->category);
    
        // Filter itineraries by duration
        $request = new Request(['duration' => 10]);
        $response = $controller->filtre($request);
        $responseData = json_decode($response->getContent()); // Convert response data to array
    
        $this->assertCount(1, $responseData->data); // Ensure data is iterable
        $this->assertEquals(10, $responseData->data[0]->duration);
    }
    
    
    
}