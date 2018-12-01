<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 30/11/2018
 * Time: 21:32
 */
namespace App\Services;

use App\Repositories\Currency\CurrencyRepositoryContract;

class CurrencyHistoryRangeConvertService
{
    public function handle(array $requestData)
    {
        $currencyData = resolve(CurrencyRepositoryContract::class)
            ->getSpecificRangeDate($requestData);

        return self::prepareResponseData($requestData, $currencyData);
    }

    private static function prepareResponseData($requestData, $currencyData)
    {
        return [
            'start_at' => $currencyData['start_at'],
            'end_at' => $currencyData['end_at'],
            'rates' => self::calculateRates($currencyData, $requestData)
        ];

    }

    private static function calculateRates(array $currencyData, $requestData)
    {
        $to = strtoupper($requestData['to']);

        return collect($currencyData['rates'])->sortBy(function(array $item, $key) {
            return $key;
        })->map(function ($value, $key) use ($currencyData, $requestData, $to) {
            return [
                'date' => $key,
                $currencyData['base'] => (int) $requestData['amount'],
                $to => $requestData['amount'] * $value[$to]
            ];
        });
    }
}
