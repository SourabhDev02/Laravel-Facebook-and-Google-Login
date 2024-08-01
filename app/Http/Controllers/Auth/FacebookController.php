<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
{
    $user = Socialite::driver('facebook')->user();

    // Check if the user is already logged in
    if (Auth::check()) {
        $currentUser = Auth::user();

        // Update the current user's Facebook details if they match
        if ($currentUser->email === $user->getEmail()) {
            $currentUser->facebook_id = $user->getId();
            $currentUser->avatar = $user->getAvatar();
            $currentUser->save();
        }

        return redirect()->intended('dashboard'); // Redirect to intended URL
    }

    // Check if a user with the same Facebook ID already exists
    $existingUser = User::where('facebook_id', $user->getId())->first();
    
    // Check if a user with the same email already exists
    $existingUserEmail = User::where('email', $user->getEmail())->first();

    if ($existingUserEmail) {
        if ($existingUser) {
            // Log the user in
            Auth::login($existingUser);
        } else {
            // Update the existing user's Facebook details
            $existingUserEmail->facebook_id = $user->getId();
            $existingUserEmail->avatar = $user->getAvatar();
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
                'facebook_id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'password' => bcrypt('admin@1234'), // Generate a random password
                'avatar' => $user->getAvatar(),
            ]);

            Auth::login($newUser);
        }
    }

    return redirect()->intended('dashboard'); // Redirect to intended URL
}


}
