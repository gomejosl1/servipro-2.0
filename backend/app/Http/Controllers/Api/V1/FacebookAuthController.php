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

class FacebookAuthController extends Controller
{
    /**
     * Generate the Facebook OAuth redirect URL for the SPA to follow.
     */
    public function redirect(): JsonResponse
    {
        $url = Socialite::driver('facebook')
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        return response()->json([
            'data' => ['url' => $url],
        ]);
    }

    /**
     * Handle the OAuth callback from Facebook: find or create the user, link
     * their social account, and log them in via Sanctum.
     */
    public function callback(): JsonResponse|UserResource
    {
        try {
            $facebookUser = Socialite::driver('facebook')->stateless()->user();
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Unable to authenticate with Facebook.',
            ], 422);
        }

        if (! $facebookUser->getEmail()) {
            return response()->json([
                'message' => 'Facebook account did not provide a verified email address.',
            ], 422);
        }

        $user = $this->findOrCreateUser($facebookUser);

        Auth::login($user);

        return new UserResource($user);
    }

    /**
     * Find the user linked to the Facebook account, link an existing user by
     * email, or create a brand new user + social account.
     */
    private function findOrCreateUser(SocialiteUser $facebookUser): User
    {
        $socialAccount = SocialAccount::query()
            ->where('provider', 'facebook')
            ->where('provider_id', $facebookUser->getId())
            ->first();

        if ($socialAccount) {
            $user = $socialAccount->user;
        } else {
            $user = User::query()->where('email', $facebookUser->getEmail())->first();

            if (! $user) {
                $user = User::create([
                    'name' => $facebookUser->getName() ?: $facebookUser->getNickname() ?: $facebookUser->getEmail(),
                    'email' => $facebookUser->getEmail(),
                    'password' => Hash::make(Str::random(40)),
                    'avatar' => $facebookUser->getAvatar(),
                ]);

                $user->forceFill(['email_verified_at' => now()])->save();
            }

            $socialAccount = new SocialAccount([
                'provider' => 'facebook',
                'provider_id' => $facebookUser->getId(),
            ]);
            $user->socialAccounts()->save($socialAccount);
        }

        $socialAccount->update([
            'token' => $facebookUser->token ?? null,
            'refresh_token' => $facebookUser->refreshToken ?? null,
            'expires_at' => isset($facebookUser->expiresIn) ? now()->addSeconds($facebookUser->expiresIn) : null,
        ]);

        if (! $user->avatar && $facebookUser->getAvatar()) {
            $user->update(['avatar' => $facebookUser->getAvatar()]);
        }

        return $user->fresh();
    }
}
