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
     * @OA\Get(
     *     path="/api/itineraries",
     *     summary="Get a list of Itineraries",
     *     tags={"Itineraries"},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request")
     * )
     */
    public function index()
    {
        $itineraries = Itinerary::all();
        return response()->json(['itineraries' => $itineraries], Response::HTTP_OK);
    }

    /**
     * @OA\Post(
     *     path="/api/store",
     *     security={"bearerAuth": {}},
     *     summary="Create a new itinerary",
     *     tags={"Itineraries"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "category_id", "duration", "image", "user_id"},
     *             @OA\Property(property="title", type="string", example="Sample Itinerary"),
     *             @OA\Property(property="category_id", type="integer", example="1"),
     *             @OA\Property(property="duration", type="string", example="3 days"),
     *             @OA\Property(property="image", type="string", format="binary")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Itinerary added successfully."),
     *     @OA\Response(response=400, description="Invalid request body or parameters")
     * )
     */
    public function store(Request $request)
    {  
        $userId = Auth::id();
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'duration' => 'required|string|max:255',
            'image' => 'required|image',
        ]);
       
        // Save the image
        $imagePath = explode('/', $request['image']->store('public/Images'));

        $request['user_id'] = $userId;
       
        // Create the itinerary
        $itinerary = new Itinerary();
        $itinerary->title = $request->title;
        $itinerary->category_id = $request->category_id;
        $itinerary->duration = $request->duration;
        $itinerary->image = $imagePath[2];
        $itinerary->user_id = $userId;
       
        $itinerary->save();

       

        return response()->json(['message' => 'Itinerary added successfully.'], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
 * @OA\Put(
 *     path="/api/itineraries/{id}",
 * 
 *     security={"bearerAuth": {}},
 *     summary="Update an itinerary",
 *     tags={"Itineraries"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the itinerary to update",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Data to update the itinerary",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                     description="Title of the itinerary (required)"
 *                 ),
 *                 @OA\Property(
 *                     property="duration",
 *                     type="string",
 *                     description="Duration of the itinerary (required)"
 *                 ),
 *                 @OA\Property(
 *                     property="image",
 *                     type="file",
 *                     format="binary",
 *                     description="Image file for the itinerary (optional)"
 *                 ),
 *                 @OA\Property(
 *                     property="category_id",
 *                     type="integer",
 *                     description="ID of the category for the itinerary (required)"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Itinerary updated successfully"
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid request"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Itinerary not found"
 *     )
 * )
 */
public function update(Request $request, $id)
{
    // Retrieve the itinerary to update
    $itinerary = Itinerary::findOrfail($id);

    // Check if the itinerary exists
    if (!$itinerary) {
        return response()->json(['message' => 'Itinerary not found'], 404);
    }

    // Validate the request data
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'duration' => 'required|string|max:255',
        'image' => 'nullable|image',
        'category_id' => 'required|exists:categories,id',
    ]);

    $itinerary->title = $validatedData['title'];
    $itinerary->duration = $validatedData['duration'];
    $itinerary->category_id = $validatedData['category_id'];

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('images');
        $itinerary->image = $imagePath;
    }

    $itinerary->save();

    return response()->json(['message' => 'Itinerary updated successfully', 'itinerary' => $itinerary]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    /**
     * @OA\Get(
     *     path="/api/search",
     *     summary="Search itineraries by category or duration",
     *     tags={"Itineraries"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     type="object",
     *                     required={"category"},
     *                     @OA\Property(property="category", type="string", example="Adventure")
     *                 ),
     *                 @OA\Schema(
     *                     type="object",
     *                     required={"duration"},
     *                     @OA\Property(property="duration", type="string", example="3 days")
     *                 )
     *             }
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="No itineraries found")
     * )
     */
    public function search(Request $request)
    {
        $category = $request->input('category');
        $duration = $request->input('duration');

        $query = Itinerary::query();
        
        if ($category) {
            $query->where('category_id', 'like', '%' . $category . '%');
        }
        
        if ($duration) {
            $query->where('duration', 'like', '%' . $duration . '%');
        }

        $itineraries = $query->get();
        if ($itineraries->count() > 0) {
            return response()->json(['itineraries' => $itineraries]);
        } else {
            return response()->json(['message' => 'No itineraries found.'], 404);
        }
       
    }

    /**
     * @OA\Post(
     *     path="/api/filter",
     *     summary="Filter itineraries by category or duration",
     *     tags={"Itineraries"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"category", "duration"},
     *             @OA\Property(property="category", type="string", example="Adventure"),
     *             @OA\Property(property="duration", type="string", example="3 days")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=400, description="Invalid request"),
     *     @OA\Response(response=404, description="No itineraries found")
     * )
     */    
    public function filter(Request $request)
    {
        $request->validate([
            'categorie' => 'sometimes|string|max:255',
            'duration' => 'sometimes|string|max:255',
        ]);

        $categorie = $request->input('category');
        $duration = $request->input('duration');

        $query = Itinerary::query();

        if ($categorie) {
            $query->where('category', $categorie);
        }

        if ($duration) {
            $query->where('duration', $duration);
        }

        $itineraires = $query->get();

        if ($itineraires->count() > 0) {
            return response()->json(['itineraires' => $itineraires]);
        } else {
            return response()->json(['message' => 'No itineraries found.'], 404);
        }
    }

   
}

