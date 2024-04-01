<?php

namespace App\Http\Controllers;

use App\Models\Itinerary;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class FavorisController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/itineraries/{itineraryId}/visited",
     *     summary="Add itinerary to visited list",
     *     tags={"Favorites"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="itineraryId",
     *         in="path",
     *         required=true,
     *         description="ID of the itinerary",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(response=200, description="Itinerary added to visited list successfully"),
     *     @OA\Response(response=401, description="User not authenticated"),
     *     @OA\Response(response=404, description="Itinerary not found"),
     *     @OA\Response(response=422, description="Itinerary is already in visited list")
     * )
     */
    public function addToVisited(Request $request, $itineraryId)
    {
        // Retrieve the authenticated user
        $user = Auth::user();

        // Check if the user exists
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Retrieve the itinerary
        $itinerary = Itinerary::find($itineraryId);

        // Check if the itinerary exists
        if (!$itinerary) {
            return response()->json(['message' => 'Itinerary not found'], 404);
        }

        // Check if the itinerary is already in the user's visited list
        if ($user->favoris()->where('itinerary_id', $itineraryId)->exists()) {
            return response()->json(['message' => 'Itinerary is already in visited list'], 422);
        }

        // Add the itinerary to the user's visited list
        $user->favoris()->attach($itineraryId);

        // Return a success response
        return response()->json(['message' => 'Itinerary added to visited list successfully']);
    }
}
