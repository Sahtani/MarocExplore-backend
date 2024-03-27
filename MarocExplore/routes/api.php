<?php

use App\Http\Controllers\AuthController;
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
Route::post('/login', [AuthController::class, 'login']);
Route::get('/itineraries', [ItineraryController::class, 'index']);
Route::get('/search', [ItineraryController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/store', [ItineraryController::class, 'store']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/route', function () {
        return 'route';
    });
});
