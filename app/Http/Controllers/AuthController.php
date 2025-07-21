<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        try {
            $result = $this->authService->login($credentials);

            return $this->successResponse([
                'user' => $result['user'],
                'authorization' => [
                    'token' => $result['token'],
                    'type' => 'bearer',
                ]
            ], 'User logged in successfully');
        } catch (\Exception $e) {
            return $this->unauthorizedResponse('Invalid credentials');
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            $result = $this->authService->register($request->all());

            return $this->successResponse([
                'user' => $result['user'],
                'authorization' => [
                    'token' => $result['token'],
                    'type' => 'bearer',
                ]
            ], 'User created successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function logout()
    {
        $this->authService->logout();
        return $this->successResponse(null, 'Logged out successfully');
    }

    public function refresh()
    {
        $result = $this->authService->refresh();

        return $this->successResponse([
            'user' => $result['user'],
            'authorization' => [
                'token' => $result['token'],
                'type' => 'bearer',
            ],
        ]);
    }

    public function me()
    {
        $user = $this->authService->me();
        return $this->successResponse(['user' => $user]);
    }
}
