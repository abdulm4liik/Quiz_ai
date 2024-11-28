<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
  
    public function updatePassword(Request $request)
    {
        Log::debug('Incoming password update request:', $request->all());  // Log the incoming request
    
        $validated = $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);
    
        Log::debug('Validated password data:', $validated);  // Log the validated data
    
        Auth::user()->update([
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
