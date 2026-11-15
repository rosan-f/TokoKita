<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display the specified user profile.
     * Enforces strict authorization to prevent IDOR vulnerabilities.
     */
    public function show(Request $request, mixed $id): JsonResponse
    {
        // 1. Verify that user is authenticated
        if (! Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // 2. Strict ID verification: Auth::id() === (int) $id
        if ((int) Auth::id() !== (int) $id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden. Akses ditolak. Anda hanya berhak mengakses data profil Anda sendiri.',
            ], 403);
        }

        /** @var User $user */
        $user = Auth::user();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'created_at' => $user->created_at?->toIso8601String(),
                'updated_at' => $user->updated_at?->toIso8601String(),
            ],
        ], 200);
    }
}
