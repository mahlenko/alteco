<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Requests;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Models\Stacking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StackingDeleteRequest extends FormRequest
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
            'id' => ['required', Rule::exists(Stacking::class, 'id')],
            'user_id' => ['required', Rule::exists(User::class, 'id')],
            'portfolio_id' => ['required', Rule::exists(Portfolio::class, 'id')],
            'coin_uuid' => ['required', Rule::exists(Coin::class, 'uuid')],
        ];
    }
}
