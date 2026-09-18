<?php

namespace Tests\Feature\Auth;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class FacebookOAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_endpoint_returns_facebook_oauth_url(): void
    {
        Socialite::fake('facebook');

        $response = $this->getJson('/api/v1/auth/facebook/redirect');

        $response->assertOk()
            ->assertJsonStructure(['data' => ['url']]);

        $this->assertStringContainsString(
            'socialite.fake/facebook/authorize',
            $response->json('data.url')
        );
    }

    public function test_callback_creates_new_user_and_social_account_when_non_existent(): void
    {
        $facebookUser = SocialiteUser::fake([
            'id' => '111111111',
            'name' => 'New Facebook User',
            'email' => 'new-facebook-user@example.com',
            'avatar' => 'https://example.com/avatar.jpg',
        ]);

        Socialite::fake('facebook', $facebookUser);

        $response = $this->getJson('/api/v1/auth/facebook/callback');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'email' => 'new-facebook-user@example.com',
                    'name' => 'New Facebook User',
                    'avatar' => 'https://example.com/avatar.jpg',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'new-facebook-user@example.com',
        ]);

        $this->assertDatabaseHas('social_accounts', [
            'provider' => 'facebook',
            'provider_id' => '111111111',
        ]);
    }

    public function test_callback_links_to_existing_user_by_email(): void
    {
        $user = User::factory()->create([
            'email' => 'existing-user@example.com',
        ]);

        $facebookUser = SocialiteUser::fake([
            'id' => '222222222',
            'name' => $user->name,
            'email' => 'existing-user@example.com',
        ]);

        Socialite::fake('facebook', $facebookUser);

        $response = $this->getJson('/api/v1/auth/facebook/callback');

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
            'provider' => 'facebook',
            'provider_id' => '222222222',
        ]);
    }

    public function test_callback_uses_existing_social_account_when_already_linked(): void
    {
        $user = User::factory()->create();

        $socialAccount = SocialAccount::factory()->create([
            'user_id' => $user->id,
            'provider' => 'facebook',
            'provider_id' => '333333333',
        ]);

        $facebookUser = SocialiteUser::fake([
            'id' => '333333333',
            'name' => $user->name,
            'email' => $user->email,
        ]);

        Socialite::fake('facebook', $facebookUser);

        $response = $this->getJson('/api/v1/auth/facebook/callback');

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                ],
            ]);

        $this->assertDatabaseCount('social_accounts', 1);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_callback_returns_error_when_facebook_authentication_fails(): void
    {
        Socialite::shouldReceive('driver')
            ->with('facebook')
            ->andReturnSelf();

        Socialite::shouldReceive('stateless')
            ->andReturnSelf();

        Socialite::shouldReceive('user')
            ->andThrow(new \Exception('invalid state'));

        $response = $this->getJson('/api/v1/auth/facebook/callback');

        $response->assertStatus(422)
            ->assertJson(['message' => 'Unable to authenticate with Facebook.']);

        $this->assertDatabaseCount('users', 0);
    }

    public function test_callback_returns_error_when_facebook_does_not_provide_email(): void
    {
        $facebookUser = SocialiteUser::fake([
            'id' => '444444444',
            'email' => null,
        ]);

        Socialite::fake('facebook', $facebookUser);

        $response = $this->getJson('/api/v1/auth/facebook/callback');

        $response->assertStatus(422);

        $this->assertDatabaseCount('users', 0);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
