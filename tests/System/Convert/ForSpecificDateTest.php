<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 30/11/2018
 * Time: 21:02
 */

namespace Tests\System\Convert;
use App\Repositories\Currency\CurrencyRepositoryContract;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery;
use Tests\TestCase;

class ForSpecificDateTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /**
     * @test
     * @group singleConvert
     */
    public function responseSuccess()
    {
        $this->mockCurrencyRepository();
        $response = $this->json('get', 'api/currency/convert?from=eur&to=usd&amount=10&date=2018-10-28')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'date',
                    'result' => [
                        'EUR',
                        'USD'
                    ]
                ]
        ]);
    }

    public function mockCurrencyRepository()
    {
        $repo = Mockery::mock(CurrencyRepositoryContract::class);
        $repo->shouldReceive('getSpecificDate')->andReturn([
                "date" => "2018-10-19",
                "rates" => [
                    "USD" => 1.147
                ],
                "base" => "EUR"
            ]
        );
        $this->app->instance(CurrencyRepositoryContract::class, $repo);
    }
}
