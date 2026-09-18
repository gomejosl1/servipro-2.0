<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Onboarding\BuyerOnboardingRequest;
use App\Http\Requests\Onboarding\ProviderOnboardingRequest;
use App\Http\Resources\UserResource;
use App\Models\ProviderProfile;

class OnboardingController extends Controller
{
    /**
     * Complete the onboarding flow for a user looking for products or
     * services (a buyer). This choice does not permanently restrict the
     * user's capabilities.
     */
    public function buyer(BuyerOnboardingRequest $request): UserResource
    {
        $user = $request->user();

        $user->fill($request->only(['phone', 'latitude', 'longitude']));
        $user->onboarding_completed = true;
        $user->save();

        return new UserResource($user);
    }

    /**
     * Complete the onboarding flow for a user offering products or services
     * (a provider). This choice does not permanently restrict the user's
     * capabilities.
     */
    public function provider(ProviderOnboardingRequest $request): UserResource
    {
        $user = $request->user();

        $user->fill($request->only(['phone', 'latitude', 'longitude']));
        $user->onboarding_completed = true;
        $user->save();

        ProviderProfile::query()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only(['business_name', 'provider_type', 'primary_category_id']),
        );

        return new UserResource($user->fresh()->load('providerProfile'));
    }
}
