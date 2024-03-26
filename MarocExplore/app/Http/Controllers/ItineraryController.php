<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ItineraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'duration' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'destinations' => 'required|array',
            'destinations.*' => 'exists:destinations,id',
        ]);

        // Save the image
        $imagePath = $request->file('image')->store('images');

        // Create the itinerary
        $itinerary = new Itinerary();
        $itinerary->title = $request->title;
        $itinerary->category_id = $request->category_id;
        $itinerary->duration = $request->duration;
        $itinerary->image = $imagePath;
        $itinerary->save();

        // Attach destinations to the itinerary
        $itinerary->destinations()->attach($request->destinations);

        return response()->json(['message' => 'Itinerary added successfully.'], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
}
