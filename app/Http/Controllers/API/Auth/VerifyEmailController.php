<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\RateLimiter;

class VerifyEmailController extends Controller
{
    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);

        // validate hash
        if (!hash_equals((string) $hash, sha1($user->email))) {
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'Email verified successfully.']);
    }

    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        $key = 'resend-verify:' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json(['message' => 'Too many requests. Try later.'], 429);
        }
        RateLimiter::hit($key, 60);

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email resent.']);
    }
}
