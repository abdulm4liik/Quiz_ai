<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'], // Built-in rule for current password
            'password' => ['required', Password::defaults(), 'confirmed'], // Password confirmation
        ]);
    
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
    
        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }


    public function updateName(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->fill($validated)->save();

        return response()->json([
            'message' => 'Name updated successfully.',
            'user' => $request->user(),
        ]);
    }
}
