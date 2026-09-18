<?php

namespace Tests\Feature\Auth;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleOAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_endpoint_returns_google_oauth_url(): void
    {
        Socialite::fake('google');

        $response = $this->getJson('/api/v1/auth/google/redirect');

        $response->assertOk()
            ->assertJsonStructure(['data' => ['url']]);

        $this->assertStringContainsString(
            'socialite.fake/google/authorize',
            $response->json('data.url')
        );
    }

    public function test_callback_creates_new_user_and_social_account_when_non_existent(): void
    {
        $googleUser = SocialiteUser::fake([
            'id' => '111111111',
            'name' => 'New Google User',
            'email' => 'new-google-user@example.com',
            'avatar' => 'https://example.com/avatar.jpg',
        ]);

        Socialite::fake('google', $googleUser);

        $response = $this->getJson('/api/v1/auth/google/callback');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'email' => 'new-google-user@example.com',
                    'name' => 'New Google User',
                    'avatar' => 'https://example.com/avatar.jpg',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'new-google-user@example.com',
        ]);

        $this->assertDatabaseHas('social_accounts', [
            'provider' => 'google',
            'provider_id' => '111111111',
        ]);
    }

    public function test_callback_links_to_existing_user_by_email(): void
    {
        $user = User::factory()->create([
            'email' => 'existing-user@example.com',
        ]);

        $googleUser = SocialiteUser::fake([
            'id' => '222222222',
            'name' => $user->name,
            'email' => 'existing-user@example.com',
        ]);

        Socialite::fake('google', $googleUser);

        $response = $this->getJson('/api/v1/auth/google/callback');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'email' => 'existing-user@example.com',
                ],
            ]);

        $this->assertDatabaseCount('users', 1);

        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => '222222222',
        ]);
    }

    public function test_callback_uses_existing_social_account_when_already_linked(): void
    {
        $user = User::factory()->create();

        $socialAccount = SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => '333333333',
        ]);

        $googleUser = SocialiteUser::fake([
            'id' => '333333333',
            'name' => $user->name,
            'email' => $user->email,
        ]);

        Socialite::fake('google', $googleUser);

        $response = $this->getJson('/api/v1/auth/google/callback');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                ],
            ]);

        $this->assertDatabaseCount('social_accounts', 1);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_callback_returns_error_when_google_authentication_fails(): void
    {
        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturnSelf();

        Socialite::shouldReceive('stateless')
            ->andReturnSelf();

        Socialite::shouldReceive('user')
            ->andThrow(new \Exception('invalid state'));

        $response = $this->getJson('/api/v1/auth/google/callback');

        $response->assertStatus(422)
            ->assertJson(['message' => 'Unable to authenticate with Google.']);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_callback_returns_error_when_google_does_not_provide_email(): void
    {
        $googleUser = SocialiteUser::fake([
            'id' => '444444444',
            'email' => null,
        ]);

        Socialite::fake('google', $googleUser);

        $response = $this->getJson('/api/v1/auth/google/callback');

        $response->assertStatus(422);

        $this->assertDatabaseCount('users', 0);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
