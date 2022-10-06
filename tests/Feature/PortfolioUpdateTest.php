<?php

namespace Tests\Feature;

use App\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\CreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Actions\UpdateAction;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PortfolioUpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;
    private Portfolio $portfolio;

    public function test_update()
    {
        $new_name = ucfirst('New name');
        UpdateAction::handle($this->user, $this->portfolio, ['name' => $new_name]);

        $this->assertEquals($new_name, $this->portfolio->name);
    }

    public function test_ucfirst_name()
    {
        UpdateAction::handle($this->user, $this->portfolio, ['name' => 'new name']);
        $this->assertEquals('New name', $this->portfolio->name);
    }

    public function test_other_user()
    {
        $other_user = User::factory()->create();

        $this->expectExceptionCode(403);
        UpdateAction::handle($other_user, $this->portfolio, ['name' => 'new name']);
    }

    public function test_admin_user()
    {
        $admin = User::factory()->admin()->create();
        $new_name = ucfirst($this->faker->word);

        UpdateAction::handle($admin, $this->portfolio, ['name' => $new_name]);

        $this->assertEquals($new_name, $this->portfolio->name);
    }

    public function test_unauthorized_user()
    {
        $this->postJson(route('api.portfolio.store'))
            ->assertUnauthorized();
    }

    public function test_request()
    {
        $name = $this->faker->word;

        $response = $this->actingAs($this->user)
            ->postJson(
                route('api.portfolio.store', $this->portfolio->id),
                ['name' => $name])
            ->assertOk();

        $this->assertTrue($response->json('ok'));
        $this->assertEquals($this->portfolio->id, $response->json('data')['id']);
        $this->assertEquals(ucfirst($name), $response->json('data')['name']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->portfolio = CreateAction::handle($this->user, 'name');
    }
}
