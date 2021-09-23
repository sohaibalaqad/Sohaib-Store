<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\AccessTokensController;

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

Route::post('auth/tokens', [AccessTokensController::class, 'store']);
Route::delete('auth/tokens', [AccessTokensController::class, 'destroy'])
    ->middleware('auth:sanctum');

Route::apiResource('categories' , CategoriesController::class)
    ->middleware('auth:sanctum');
