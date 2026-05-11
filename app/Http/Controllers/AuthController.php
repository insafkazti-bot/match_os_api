<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateMyAdminRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $admin = Admin::where('email', $credentials['email'])->first();
        if (! $admin || ! Hash::check($credentials['password'], $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json(['token' => $token, 'admin' => new AdminResource($admin)]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        if (! $user || ! ($user instanceof Admin)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return new AdminResource($user);
    }

    public function updateMe(UpdateMyAdminRequest $request)
    {
        $user = $request->user();
        if (! $user || ! ($user instanceof Admin)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $validated = $request->validated();

            if (array_key_exists('email', $validated) && $validated['email'] !== $user->email) {
                $request->validate([
                    'email' => ['email', 'unique:admins,email,' . $user->id],
                ]);
            }

            if (array_key_exists('password', $validated) && $validated['password']) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return new AdminResource($user->fresh());
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to update account'], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
