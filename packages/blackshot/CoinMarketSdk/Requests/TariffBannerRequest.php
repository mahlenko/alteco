<?php

namespace Blackshot\CoinMarketSdk\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TariffBannerRequest extends FormRequest
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
            'uuid' => ['nullable', Rule::exists('tariff_banners', 'uuid')],
            'tariff_id' => ['required', Rule::exists('tariffs', 'id')],
            'picture' => ['required_without:body', 'image'],
            'body' => ['required_without:picture', 'string'],
            'start' => ['required', 'date'],
            'end' => ['nullable', 'date', 'after:start'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
