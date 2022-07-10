<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotebookController;

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

Route::post('/v1/me', [AuthController::class, 'me'])->middleware('auth:sanctum');

/**
 * Public routes
 */
Route::group([
    'prefix' => 'v1'
], function () {
    // User registration endpoint
    Route::post('/register', [AuthController::class, 'register']);
    // User login endpoint
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/notebook', [NotebookController::class, 'index']);
    Route::get('/notebook/{id}', [NotebookController::class, 'show']);
    Route::get('/notebook/search/{name}', [NotebookController::class, 'search']);
});

/**
 * Protected routes
 */
Route::group([
    'prefix' => 'v1',
    'middleware' => ['auth:sanctum']
], function () {
    Route::post('/notebook', [NotebookController::class, 'add']);
    Route::post('/notebook/{id}', [NotebookController::class, 'update']);
    Route::delete('/notebook/{id}', [NotebookController::class, 'destroy']);
    Route::get('/logout', [AuthController::class, 'logout']);
});
