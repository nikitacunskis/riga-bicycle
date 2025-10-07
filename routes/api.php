<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SelectorController;
use App\Http\Controllers\SocialPostController;
use App\Http\Controllers\MapController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix("/data")->group(function () {
    Route::get('/', [SelectorController::class, 'apiReport']);
});


Route::get('/map/places', [MapController::class, 'places']);
Route::get('/map/events', [MapController::class, 'events']); // for a dropdown

//Route::post('/social/post', SocialPostController::class);

