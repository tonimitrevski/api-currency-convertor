<?php
/**
 * Created by PhpStorm.
 * User: toni
 * Date: 01/12/2018
 * Time: 13:16
 */

namespace App\Http\Requests;


use Illuminate\Validation\Rule;

trait DefaultValidationRulesTrait
{
    private static $currencies = [
        'usd',
        'eur',
        'gbp'
    ];

    protected function defaultRules()
    {
        return [
            'from' => [
                'required',
                'max:3',
                'min:3',
                Rule::in($this::$currencies)
            ],
            'to' => [
                'required',
                'max:3',
                'min:3',
                Rule::in($this::$currencies)
            ],
            'amount' => 'required|int',
        ];
    }
}
