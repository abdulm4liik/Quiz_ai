<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string'],
            'name' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // If validation fails, return errors
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user(); // Get the authenticated user

        // Verify the current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'errors' => ['current_password' => ['The current password is incorrect.']],
            ], 422);
        }

        // Update the name if provided
        if ($request->has('name')) {
            $user->name = $request->name;
        }

        // Update the password if a new password is provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Save the updated user
        $user->save();

        // Return a response indicating success
        return response()->json([
            'message' => 'Profile updated successfully!',
            'user' => $user,
        ]);
    }
}
