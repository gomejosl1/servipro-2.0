<?php

namespace Tests\Unit\Models;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserSocialAccountRelationshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_social_accounts(): void
    {
        $user = User::factory()->create();
        $socialAccount = SocialAccount::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertTrue($user->socialAccounts->contains($socialAccount));
        $this->assertInstanceOf(User::class, $socialAccount->user);
    }

    public function test_social_account_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $socialAccount = SocialAccount::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertSame($user->id, $socialAccount->user->id);
    }

    public function test_provider_and_provider_id_combination_is_unique(): void
    {
        $existing = SocialAccount::factory()->create([
            'provider' => 'google',
            'provider_id' => 'duplicate-id',
        ]);

        $this->expectException(QueryException::class);

        SocialAccount::factory()->create([
            'provider' => 'google',
            'provider_id' => 'duplicate-id',
        ]);
    }
}
