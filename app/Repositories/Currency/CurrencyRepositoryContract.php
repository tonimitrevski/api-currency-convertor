<?php
namespace App\Repositories\Currency;

interface CurrencyRepositoryContract
{
    public function getSpecificDate(array $filters);

    public function getSpecificRangeDate(array $filters);
}
