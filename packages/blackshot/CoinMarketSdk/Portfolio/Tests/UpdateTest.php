<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\StoreAction;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Portfolio $portfolio;

    public function test_update()
    {
        $new_name = ucfirst('New name');
        StoreAction::handle($this->user, $new_name, $this->portfolio);

        $this->assertEquals($new_name, $this->portfolio->name);
    }

    public function test_ucfirst_name()
    {
        StoreAction::handle($this->user, 'new name', $this->portfolio);
        $this->assertEquals('New name', $this->portfolio->name);
    }

    public function test_other_user()
    {
        $other_user = User::factory()->create();

        $this->expectExceptionCode(403);
        StoreAction::handle($other_user, 'new name', $this->portfolio);
    }

    public function test_admin_user()
    {
        $admin = User::factory()->admin()->create();
        $new_name = ucfirst('New name');

        StoreAction::handle($admin, $new_name, $this->portfolio);

        $this->assertEquals($new_name, $this->portfolio->name);
    }

    public function test_unauthorized_user()
    {
        $this->postJson(route('api.portfolio.store'))
            ->assertUnauthorized();
    }

    public function test_request()
    {
        $response = $this
            ->actingAs($this->user)
            ->postJson(
                route('api.portfolio.store', $this->portfolio->id),
                ['name' => $name = 'new name'])
            ->assertOk();

        $this->assertTrue($response->json('ok'));
        $this->assertEquals($this->portfolio->id, $response->json('data')['id']);
        $this->assertEquals(ucfirst($name), $response->json('data')['name']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->portfolio = StoreAction::handle($this->user, 'name');
    }
}
