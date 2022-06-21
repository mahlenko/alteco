<?php

namespace Blackshot\CoinMarketSdk\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TariffRequest extends FormRequest
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
            'id' => ['nullable', Rule::exists('tariffs', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'days' => ['required', 'numeric', 'min:0', Rule::notIn(['0'])],
            'default' => ['nullable'],
            'free' => ['nullable'],
            'move' => ['nullable'],
        ];
    }
}
