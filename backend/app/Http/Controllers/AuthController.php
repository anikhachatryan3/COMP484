<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    // For registering
    public function register2(Request $request) {
        $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'role' => 'required|exists:roles,name',
            'password' => 'required',
        ]);

        $user = new User;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->role_id = Role::where('name', $request->role)->firstOrFail()->id;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->role;

        // return 'here aziz jan';
        return response()->json([
            'user' => $user
        ], 201);
    }

    // For logging in
    public function login2(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if($user) {
            if (Hash::check($request->password, $user->password)) {
                Auth::login($user);
                $user->role;
                return response()->json([
                    'user' => $user
                ], 200);
            }
            else {
                return response()->json([
                    'errors' => [
                        'other' => 'Email or password does not match.'
                    ]
                ], 404);
            }
        }
        else {
            return response()->json([
                'errors' => [
                    'other' => 'Email or password does not match.'
                ]
            ], 404);
        }
    }
}
