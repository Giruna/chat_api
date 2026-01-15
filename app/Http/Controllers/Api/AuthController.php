<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param $id
     * @param string $hash
     * @return JsonResponse
     */
     public function verify($id, string $hash): JsonResponse
    {
        return $this->authService->verifyEmail($id, $hash);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return $this->authService->login(
            $validated['email'],
            $validated['password']
        );
    }
}
