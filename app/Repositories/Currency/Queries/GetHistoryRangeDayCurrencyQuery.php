<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 30/11/2018
 * Time: 21:48
 */
namespace App\Repositories\Currency\Queries;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class GetHistoryRangeDayCurrencyQuery
{

    /**
     * @param Client $client
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(Client $client, $data)
    {
        return $client->send($this->request(), [
            'query' => $this->apply($data)
        ]);
    }

    public function request()
    {
        return new Request('PUT', 'api.exchangeratesapi.io/history');
    }

    public function apply($data)
    {
        return [
            'start_at' => $data['start_at'],
            'end_at' => $data['end_at'],
            'base' => strtoupper($data['from']),
            'symbols' => strtoupper($data['to'])
        ];
    }
}
