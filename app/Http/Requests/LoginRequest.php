<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{
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
        return [
            'email' => ['required_if:grant_type,password,empty_when:grant_type,refresh_token|email|unique:users,email'],
            'password' => ['required_if:grant_type,password'],
            'grant_type' => ['required', Rule::in(['password', 'refresh_token'])],
            'refresh_token' => ['required_if:grant_type,refresh_token'],
        ];
    }
}
