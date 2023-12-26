<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;

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

Route::prefix('store')->group(function () {
    Route::post('/create-store', [StoreController::class, 'createStore']);
    Route::get('/get-store/{id}', [StoreController::class, 'getStoreById']);
});

Route::prefix('client')->group(function () {
    Route::post('/register', [ClientController::class, 'registerClient']);
    Route::get('/get-by-firebase/{email}/{access_token}', [ClientController::class, 'getClientByFirebase']);
});

Route::prefix('auth')->group(function () {
    Route::post('/refresh-access-token', [AuthController::class, 'refreshAccessToken']);
    Route::post('/create-access-token', [AuthController::class, 'createAccessToken']);
    Route::post('/create-refresh-token', [AuthController::class, 'createRefreshToken']);
});
