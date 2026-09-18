<?php

namespace Tests\Feature\Onboarding;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_buyer_onboarding(): void
    {
        $response = $this->postJson('/api/v1/onboarding/buyer', []);

        $response->assertStatus(401);
    }

    public function test_guest_cannot_access_provider_onboarding(): void
    {
        $response = $this->postJson('/api/v1/onboarding/provider', []);

        $response->assertStatus(401);
    }

    public function test_buyer_can_complete_onboarding(): void
    {
        $user = User::factory()->create(['onboarding_completed' => false]);

        $response = $this->actingAs($user)->postJson('/api/v1/onboarding/buyer', [
            'phone' => '+1 555 000 1111',
            'latitude' => 18.4861,
            'longitude' => -69.9312,
        ]);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'phone' => '+1 555 000 1111',
                    'onboarding_completed' => true,
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'phone' => '+1 555 000 1111',
            'onboarding_completed' => true,
        ]);
    }

    public function test_buyer_onboarding_does_not_require_location(): void
    {
        $user = User::factory()->create(['onboarding_completed' => false]);

        $response = $this->actingAs($user)->postJson('/api/v1/onboarding/buyer', []);

        $response->assertOk();

        $this->assertTrue($user->refresh()->onboarding_completed);
    }

    public function test_provider_can_complete_onboarding(): void
    {
        $user = User::factory()->create(['onboarding_completed' => false]);

        $response = $this->actingAs($user)->postJson('/api/v1/onboarding/provider', [
            'business_name' => 'Acme Repairs',
            'phone' => '+1 555 222 3333',
            'provider_type' => 'BUSINESS',
            'primary_category_id' => 5,
            'latitude' => 18.4861,
            'longitude' => -69.9312,
        ]);

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'onboarding_completed' => true,
                    'provider_profile' => [
                        'business_name' => 'Acme Repairs',
                        'provider_type' => 'BUSINESS',
                        'primary_category_id' => 5,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'phone' => '+1 555 222 3333',
            'onboarding_completed' => true,
        ]);

        $this->assertDatabaseHas('provider_profiles', [
            'user_id' => $user->id,
            'business_name' => 'Acme Repairs',
            'provider_type' => 'BUSINESS',
            'primary_category_id' => 5,
        ]);
    }

    public function test_provider_onboarding_choice_does_not_restrict_user_from_buyer_flow(): void
    {
        $user = User::factory()->create(['onboarding_completed' => false]);

        $this->actingAs($user)->postJson('/api/v1/onboarding/provider', [
            'business_name' => 'Acme Repairs',
            'provider_type' => 'INDIVIDUAL',
        ])->assertOk();

        $response = $this->actingAs($user->refresh())->postJson('/api/v1/onboarding/buyer', [
            'phone' => '+1 555 444 5555',
        ]);

        $response->assertOk();
    }

    public function test_provider_onboarding_requires_business_name_and_provider_type(): void
    {
        $user = User::factory()->create(['onboarding_completed' => false]);

        $response = $this->actingAs($user)->postJson('/api/v1/onboarding/provider', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['business_name', 'provider_type']);
    }

    public function test_provider_onboarding_rejects_invalid_provider_type(): void
    {
        $user = User::factory()->create(['onboarding_completed' => false]);

        $response = $this->actingAs($user)->postJson('/api/v1/onboarding/provider', [
            'business_name' => 'Acme Repairs',
            'provider_type' => 'NOT_A_TYPE',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['provider_type']);
    }

    public function test_onboarding_rejects_out_of_range_coordinates(): void
    {
        $user = User::factory()->create(['onboarding_completed' => false]);

        $response = $this->actingAs($user)->postJson('/api/v1/onboarding/buyer', [
            'latitude' => 200,
            'longitude' => -69.9312,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['latitude']);
    }
}
