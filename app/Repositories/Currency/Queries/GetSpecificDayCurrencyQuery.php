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

class GetSpecificDayCurrencyQuery
{

    /**
     * @param Client $client
     * @param $data
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(Client $client, $data)
    {
        $request = $this->date($data['date'] ?? 'latest');

        return $client->send($request, [
            'query' => $this->apply($data)
        ]);
    }

    public function date($date)
    {
        return new Request('PUT', 'api.exchangeratesapi.io/'.$date);
    }

    public function apply($data)
    {
        return [
            'base' => strtoupper($data['from']),
            'symbols' => strtoupper($data['to'])
        ];
    }
}
