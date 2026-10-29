<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the logged-in user's profile.
     */
    public function show(Request $request): View
    {
        $user = $request->user()->load('ptjDepartment');

        return view('profile.show', [
            'user' => $user,
        ]);
    }


    /**
     * Display the profile edit page.
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('ptjDepartment');

        return view('profile.edit', [
            'user' => $user,
        ]);
    }


    /**
     * Update editable profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validated();

        $user->email = $validated['email'];

        $user->user_phone_number =
            $validated['user_phone_number'] ?? null;


        // Only change password if user entered a new one.
        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }


        $user->save();


        return redirect()
            ->route('profile.show')
            ->with('status', 'profile-updated');
    }
}