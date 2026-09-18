<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleAuthController extends Controller
{
    /**
     * Generate the Google OAuth redirect URL for the SPA to follow.
     */
    public function redirect(): JsonResponse
    {
        $url = Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return response()->json([
            'data' => ['url' => $url],
        ]);
    }

    /**
     * Handle the OAuth callback from Google: find or create the user, link
     * their social account, and log them in via Sanctum.
     */
    public function callback(): JsonResponse|UserResource
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Unable to authenticate with Google.',
            ], 422);
        }

        if (! $googleUser->getEmail()) {
            return response()->json([
                'message' => 'Google account did not provide a verified email address.',
            ], 422);
        }

        $user = $this->findOrCreateUser($googleUser);

        Auth::login($user);

        return new UserResource($user);
    }

    /**
     * Find the user linked to the Google account, link an existing user by
     * email, or create a brand new user + social account.
     */
    private function findOrCreateUser(SocialiteUser $googleUser): User
    {
        $socialAccount = SocialAccount::query()
            ->where('provider', 'google')
            ->where('provider_id', $googleUser->getId())
            ->first();

        if ($socialAccount) {
            $user = $socialAccount->user;
        } else {
            $user = User::query()->where('email', $googleUser->getEmail())->first();

            if (! $user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: $googleUser->getEmail(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(40)),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                $user->forceFill(['email_verified_at' => now()])->save();
            }

            $socialAccount = new SocialAccount([
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
            ]);
            $user->socialAccounts()->save($socialAccount);
        }

        $socialAccount->update([
            'token' => $googleUser->token ?? null,
            'refresh_token' => $googleUser->refreshToken ?? null,
            'expires_at' => isset($googleUser->expiresIn) ? now()->addSeconds($googleUser->expiresIn) : null,
        ]);

        if (! $user->avatar && $googleUser->getAvatar()) {
            $user->update(['avatar' => $googleUser->getAvatar()]);
        }

        return $user->fresh();
    }
}
