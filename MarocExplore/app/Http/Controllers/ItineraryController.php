<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itineraries = Itinerary::all();
        
        return response()->json(['itineraries' => $itineraries], Response::HTTP_OK);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {  $userId = Auth::id();
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'duration' => 'required|string|max:255',
            'image' => 'required|image',
            'user_id' => $userId,
        ]);

        // Save the image
        $imagePath = explode('/', $request['image']->store('public/Images'));
      
        // Create the itinerary
        $itinerary = new Itinerary();
        $itinerary->title = $request->title;
        $itinerary->category_id = $request->category_id;
        $itinerary->duration = $request->duration;
        $itinerary->image = $imagePath;
       
        $itinerary->save();

       

        return response()->json(['message' => 'Itinerary added successfully.'], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      return 'text';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function search(Request $request)
    {
        // Retrieve search criteria from the request
        $category = $request->input('category');
        $duration = '4 days';

        // Query itineraries based on the provided search criteria
        $query = Itinerary::query();
        
        // if ($category) {
        //     $query->where('category', 'like', '%' . $category . '%');
        // }
        
        if ($duration) {
            $query->where('duration', 'like', '%' . $duration . '%');
        }

        $itineraries = $query->get();

        // Return the filtered itineraries
        return response()->json(['itineraries' => $itineraries]);
    }
   
}

