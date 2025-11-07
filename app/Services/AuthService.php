<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthService
{
    /**
     * Verify a user's email.
     *
     * @param int $userId
     * @param string $hash
     * @return JsonResponse
     */
    public function verifyEmail(int $userId, string $hash): JsonResponse
    {
        $user = User::getById($userId);

        if (!$user || !hash_equals($hash, sha1($user->email))) {
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email verified successfully.']);
    }

    /**
     * Attempt to log in a user.
     *
     * @param string $email
     * @param string $password
     * @return JsonResponse
     */
    public function login(string $email, string $password): JsonResponse
    {
        $user = User::findByEmail($email);

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Wrong email or password'], 401);
        }

        if (is_null($user->email_verified_at)) {
            return response()->json([
                'success' => false,
                'message' => 'To log in, you must first confirm your email address.'
            ], 403);
        }

        $token = $user->createToken('FrontendApp')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token,
        ]);
    }
}
