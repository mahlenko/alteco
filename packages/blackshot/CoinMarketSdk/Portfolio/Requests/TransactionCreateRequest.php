<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Requests;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransactionTypeEnum;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransferTypeEnum;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class TransactionCreateRequest extends FormRequest
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
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'fee' => ['nullable','numeric', 'min:0'],
            'date_at' => ['date'],
            'type' => ['required', new Enum(TransactionTypeEnum::class)],
            'transfer_type' => ['nullable', new Enum(TransferTypeEnum::class)],
        ];
    }
}
