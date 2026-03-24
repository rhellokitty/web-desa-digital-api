<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoriesInterface;
use Illuminate\Support\Facades\Auth;

class AuthRepositories implements AuthRepositoriesInterface
{
    public function login(array $data)
    {
        if (!Auth::guard('web')->attempt($data)) {
            return response([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'success' => true,
            'token' => $token,
            'message' => 'Login Success',
        ]);
    }

    public function logout()
    {
        $user = Auth::user();

        $user->currentAccessToken()->delete();

        $response = [
            'success' => true,
            'message' => 'Logout Success'
        ];

        return response($response, 200);
    }

    public function me()
    {
        if (Auth::check()) {
            $user = Auth::user();

            $user->load('roles.permissions');

            $permissions = $user->roles->flatMap->permissions->pluck('name');

            $role = $user->roles->first()->name;

            return response()->json([
                'message' => 'User Data',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'permissions' => $permissions,
                    'role' => $role
                ],
            ]);
        }
        return response([
            'message' => 'You are not logged in',
        ]);
    }
}
