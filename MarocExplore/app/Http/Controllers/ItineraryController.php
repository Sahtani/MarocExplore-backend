<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Itinerary;
use App\Models\User;
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
        $itineraries = Itinerary::with('destinations')->with('user')->with('category')->get();
        return response()->json(['itineraries' => $itineraries], Response::HTTP_OK);
    }

    public function categories()
    {
        $categories = Category::all();

        return response()->json([
            'status' => 'success',
            'categories' => $categories,
        ]);
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
            'duration' => 'required|integer',
            'image' => 'required',
            'destinations' => 'required|array|min:2', // At least one destination is required
            'destinations.*.name' => 'required|string|max:255',
            'destinations.*.accommodation' => 'required|string|max:255',
            'destinations.*.places' => 'nullable|string',
            'destinations.*.activities' => 'nullable|string',
            'destinations.*.dishes' => 'nullable|string',
        ]);

        // Save the image
        $imagePath = $request->file('image')->store('images', 'public');
        $imageUrl = asset('storage/' . $imagePath);

        $request['user_id'] = $userId;

        // Create the itinerary
        $itinerary = new Itinerary();
        $itinerary->title = $request->title;
        $itinerary->category_id = $request->category_id;
        $itinerary->duration = $request->duration;
        $itinerary->image = $imageUrl;
        $itinerary->user_id = $userId;

        $itinerary->save();

        foreach ($request->destinations as $destinationData) {
            $destination = new Destination();
            $destination->name = $destinationData['name'];
            $destination->accommodation = $destinationData['accommodation'];
            $destination->places = $destinationData['places'];
            $destination->activities = $destinationData['activities'];
            $destination->dishes = $destinationData['dishes'];
            $destination->itinerary_id = $itinerary->id;
            $destination->save();
        }

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
        $user_id = Auth::user()->id;
        // Retrieve the itinerary to update

        $itinerary = Itinerary::findOrfail($id);

        // Check if the itinerary exists

        // return response()->json(['message' => 'error'], 401);

        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }
        if ($itinerary['user_id'] === $user_id) {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'duration' => 'required|integer|max:255',
                'image' => 'nullable',
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

        return response()->json(['message' => 'error']);
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
    // public function search(Request $request)
    // {
    //     $category = $request->input('category');
    //     $duration = $request->input('duration');

    //     $query = Itinerary::query();

    //     if ($category) {
    //         $query->where('category_id', 'like', '%' . $category . '%');
    //     }

    //     if ($duration) {
    //         $query->where('duration', 'like', '%' . $duration . '%');
    //     }

    //     $itineraries = $query->get();
    //     if ($itineraries->count() > 0) {
    //         return response()->json(['itineraries' => $itineraries]);
    //     } else {
    //         return response()->json(['message' => 'No itineraries found.'], 404);
    //     }

    // }
    public function search(Request $request)
    {
        // Get input values
        $category = $request->input('category');
        $duration = $request->input('duration');
        $title = $request->input('title');

        // Validate input
        $request->validate([
            'category' => 'nullable|string',
            'duration' => 'nullable|string',
            'title' => 'nullable|string'
        ]);

        // Query itineraries
        $query = Itinerary::query();

        // Apply filters
        if ($category) {
            $query->whereHas('category_id', function ($query) use ($category) {
                $query->where('name', 'like', '%' . $category . '%');
            });
        }

        if ($duration) {
            // Adjust this condition based on the actual data type of 'duration'
            $query->where('duration', 'like', '%' . $duration . '%');
        }
        if ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        }
        // Eager load relationships to avoid N+1 query issues
        $itineraries = $query->with('destinations')->with('user')->with('category')->get();
        // Check if any itineraries are found
        if ($itineraries->isEmpty()) {
            return response()->json(['message' => 'No itineraries found.'], 404);
        }

        // Return the result
        return response()->json($itineraries);
    }


    /**
     * @OA\Get(
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
        // $request->validate([
        //     'categorie' => 'sometimes|string|max:255',
        //     'duration' => 'sometimes|string|max:255',
        // ]);

        $categorie = $request->input('category_id');
        $duration = $request->input('duration');

        $query = Itinerary::query();

        // if ($categorie) {
        //     $query->where('category_id', $categorie);
        // }
        if ($categorie) {
            // dd($categorie);
            $query->where('category_id', $categorie);
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
