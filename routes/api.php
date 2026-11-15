<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - TokoKita (Lab Keamanan Jaringan)
|--------------------------------------------------------------------------
*/

// Public Authentication Endpoints (with generalized error messages)
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:15,1');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:15,1');

// Protected Endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => 'success',
            'data' => $request->user(),
        ]);
    });

    /**
     * Endpoint /api/users/{id}
     * Hanya mengembalikan data milik user yang sedang login (cek Auth::id() === $id),
     * 401 jika unauthenticated (ditangani middleware auth:sanctum),
     * 403 jika mengakses id user lain.
     */
    Route::get('/users/{id}', [UserController::class, 'show']);
});
