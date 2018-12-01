<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 30/11/2018
 * Time: 21:32
 */

namespace App\Services;

use App\Repositories\Currency\CurrencyRepositoryContract;

class CurrencyConvertService
{
    public function handle(array $requestData)
    {
        $currencyData = resolve(CurrencyRepositoryContract::class)
            ->getSpecificDate($requestData);

        if (!count($currencyData)) {
            return [];
        }

        return $this->prepareResponseData($requestData, $currencyData);
    }

    private function prepareResponseData($requestData, $currencyData)
    {
        $to = strtoupper($requestData['to']);

        return [
            'date' => $currencyData['date'],
            'result' => [
                $currencyData['base'] => (int) $requestData['amount'],
                $to => $requestData['amount'] * $currencyData['rates'][$to]
            ]
        ];
    }
}
