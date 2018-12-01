<?php
namespace App\Repositories\Currency;

use App\Repositories\Currency\Queries\GetHistoryRangeDayCurrencyQuery;
use App\Repositories\Currency\Queries\GetSpecificDayCurrencyQuery;
use GuzzleHttp\Client;

class CurrencyRepository implements CurrencyRepositoryContract
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * UserRepository constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param array $filters
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSpecificDate(array $filters)
    {
        $guzzleResponse = resolve(GetSpecificDayCurrencyQuery::class)->handle(
            $this->client,
            $filters
        );

        return json_decode($guzzleResponse->getBody(), true);
    }

    /**
     * @param array $filters
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSpecificRangeDate(array $filters)
    {
        $guzzleResponse = resolve(GetHistoryRangeDayCurrencyQuery::class)->handle(
            $this->client,
            $filters
        );

        return json_decode($guzzleResponse->getBody(), true);
    }
}
