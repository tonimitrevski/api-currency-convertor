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

class ForHistoryRangeDateTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /**
     * @test
     * @group range
     */
    public function responseSuccess()
    {
        $this->mockCurrencyRepository();
        $this->json(
            'get',
            'api/currency/convert/history?from=eur&to=usd&amount=10&start_at=2018-01-01&end_at=2018-01-10'
        )
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'start_at',
                    'end_at',
                    'rates'
                ]
        ]);

    }

    public function mockCurrencyRepository()
    {
        $repo = Mockery::mock(CurrencyRepositoryContract::class);
        $repo->shouldReceive('getSpecificRangeDate')->andReturn([
                "end_at" => "2018-01-10",
                "start_at" => "2018-01-01",
                "rates" => [
                    "2018-01-09" => [
                        "USD" => 1.1932
                    ],
                    "2018-01-03" => [
                        "USD" => 1.2023
                    ],
                    "2018-01-02" => [
                        "USD" => 1.2065
                    ]
                ],
                "base" => "EUR"
            ]
        );
        $this->app->instance(CurrencyRepositoryContract::class, $repo);
    }
}

