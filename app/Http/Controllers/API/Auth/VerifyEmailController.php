<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class VerifyEmailController extends Controller
{
    /**
     * @param $id
     * @param string $hash
     * @return JsonResponse
     */
   /* public function verify($id, string $hash): JsonResponse
    {
        $user = User::find($id);

        if (!$user || !hash_equals($hash, sha1($user->email))) {
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email verified successfully.']);
    }*/
}
