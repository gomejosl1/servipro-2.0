<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'phone' => $this->phone,
            'onboarding_completed' => $this->onboarding_completed,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'provider_profile' => $this->whenLoaded(
                'providerProfile',
                fn () => new ProviderProfileResource($this->providerProfile)
            ),
        ];
    }
}
