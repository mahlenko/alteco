<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Requests;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StackingCreateRequest extends FormRequest
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
            'user_id' => ['required', Rule::exists(User::class, 'id')],
            'portfolio_id' => ['required', Rule::exists(Portfolio::class, 'id')],
            'coin_uuid' => ['required', Rule::exists(Coin::class, 'uuid')],
            'amount' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'apy' => ['required', 'numeric', 'min:0', 'not_in:0'],
            'stacking_at' => ['required', 'date', 'before_or_equal:'. (new \DateTimeImmutable())->format('Y-m-d H:i:s')],
        ];
    }
}
