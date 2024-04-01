<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavorisController;
use App\Http\Controllers\ItineraryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function () {
//     // return $request->user();
// });
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name("login");

Route::middleware('auth:api')->group(function () {
    Route::get('/itineraries', [ItineraryController::class, 'index']);

    Route::get('/search', [ItineraryController::class, 'search']);

    Route::get('/filter', [ItineraryController::class, 'filter']);

    Route::post('/store', [ItineraryController::class, 'store']);

    Route::patch('/update/{id}', [ItineraryController::class, 'update']);

    Route::post('/itineraries/{id}/add-to-visited', [FavorisController::class, 'addToVisited']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
