<?php

use App\Http\Controllers\Ngo\ChurnController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'role:ngo'])->group(function () {
    Route::prefix('ngo/churn')->group(function () {
        Route::get('volunteers', [ChurnController::class, 'index']);
        Route::get('volunteers/{volunteerId}', [ChurnController::class, 'show']);
        Route::post('volunteers/{volunteerId}/refresh', [ChurnController::class, 'refresh']);
    });
});
