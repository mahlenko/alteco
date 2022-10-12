<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use App\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\StoreAction;
use Tests\TestCase;

class ListTest extends TestCase
{
    public function test_unauthorized_user()
    {
        $this->getJson(route('api.portfolio.home'))
            ->assertUnauthorized();
    }

    public function test_request()
    {
        $user = User::factory()->create();
        $portfolio = StoreAction::handle($user, 'default');

        $response = $this
            ->actingAs($user)
            ->getJson(route('api.portfolio.home'));

        $response->assertOk();

        $json = $response->json('data');

        $this->assertCount(1, $json);
        $this->assertEquals($user->id, $json[0]['user_id']);
        $this->assertEquals($portfolio->id, $json[0]['id']);
    }
}
