<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    /**
     * Update the user's profile picture and social links.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updateUserProfile', [
            'profile_picture' => ['nullable', 'url'],
            'twitter' => ['nullable', 'url'],
            'linkedin' => ['nullable', 'url'],
            'instagram' => ['nullable', 'url'],
        ]);

        $request->user()->update($validated);

        return back()->with('status', 'user-profile-updated');
    }
}
