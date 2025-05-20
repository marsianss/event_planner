<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Update the user's profile picture and social links.
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $request->user()->update(['profile_picture' => '/storage/' . $path]);

            return response()->json([
                'success' => true,
                'profile_picture_url' => '/storage/' . $path,
            ]);
        }

        return response()->json(['success' => false], 400);
    }
}
