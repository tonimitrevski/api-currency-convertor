<?php

namespace App\Http\Requests;

use App\Rules\DateRule;
use Illuminate\Foundation\Http\FormRequest;

class CurrencyConvertRequest extends FormRequest
{
    use DefaultValidationRulesTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge([
            'date' => [new DateRule] //2009-02-28
        ], $this->defaultRules());
    }
}
