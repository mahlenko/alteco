<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use App\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\CreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Actions\DeleteAction;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_delete()
    {
        DeleteAction::handle($this->user, $this->portfolio);
        $this->assertDatabaseCount(Portfolio::class, 0);
    }

    public function test_other_user()
    {
        $this->actingAs($this->user);

        $user_other = User::factory()->create();

        $this->expectExceptionCode(500);
        DeleteAction::handle($user_other, $this->portfolio);
    }

    public function test_admin_user()
    {
        $admin = User::factory()->admin()->create();
        DeleteAction::handle($admin, $this->portfolio);

        $this->assertDatabaseCount(Portfolio::class, 0);
    }

    public function test_unauthorized_user()
    {
        $this->deleteJson(route('api.portfolio.delete'))
            ->assertUnauthorized();
    }

    public function test_request()
    {
        $response = $this
            ->actingAs($this->user)
            ->deleteJson(route('api.portfolio.delete'), [
                'id' => $this->portfolio->id
            ])->assertOk();

        $this->assertTrue($response->json('ok'));
        $this->assertEquals($this->portfolio->id, $response->json('data'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->portfolio = CreateAction::handle($this->user, 'test');
    }
}
