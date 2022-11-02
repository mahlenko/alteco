<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Requests;

use Blackshot\CoinMarketSdk\Models\Coin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PriceCoinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'coin_uuid' => ['required', Rule::exists(Coin::class, 'uuid')],
            'date' => ['nullable', 'date']
        ];
    }
}
