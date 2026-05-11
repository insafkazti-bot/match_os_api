<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberAuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $member = Member::where('email', $credentials['email'])->first();
        if (! $member || ! Hash::check($credentials['password'], $member->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $member->createToken('member-token')->plainTextToken;

        return response()->json(['token' => $token, 'member' => $member]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}

