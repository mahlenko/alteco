<?php

namespace Blackshot\CoinMarketSdk\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CoinRequest extends FormRequest
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
            'uuid' => ['required', Rule::exists('coins')],
            'alteco' => ['required', 'integer', 'min:1', 'max:100'],
            'alteco_desc' => ['nullable', 'string', 'max:65535'],
            'description' => ['required', 'string', 'max:65535'],
        ];
    }
}
