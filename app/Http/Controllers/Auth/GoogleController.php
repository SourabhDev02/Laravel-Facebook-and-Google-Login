<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        // Check if the user is already logged in
        if (Auth::check()) {
            $currentUser = Auth::user();
            
            // Update the current user's Google details if their email matches
            if ($currentUser->email === $user->email) {
                $currentUser->google_id = $user->id;
                $currentUser->save();
            }

            // Log the user in
            return redirect()->intended('dashboard'); // Redirect to intended URL
        }

        // Find or create a user based on Google ID
        $existingUser = User::where('google_id', $user->id)->first();

        // Find or create a user based on email
        $existingUserEmail = User::where('email', $user->email)->first();

        if ($existingUserEmail) {
            if ($existingUser) {
                // Log the user in
                Auth::login($existingUser);
            } else {
                // Update the existing user's Google ID
                $existingUserEmail->google_id = $user->id;
                $existingUserEmail->save();

                Auth::login($existingUserEmail);
            }
        } else {
            if ($existingUser) {
                // Log the user in
                Auth::login($existingUser);
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => bcrypt('admin@1234'), // Generate a random password
                    'google_id' => $user->id,
                ]);

                Auth::login($newUser);
            }
        }

        return redirect()->intended('dashboard'); // Redirect to intended URL
    }


}

