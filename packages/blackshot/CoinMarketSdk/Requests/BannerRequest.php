<?php

namespace Blackshot\CoinMarketSdk\Requests;

use Blackshot\CoinMarketSdk\Enums\BannerTypes;
use Blackshot\CoinMarketSdk\Models\Banner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BannerRequest extends FormRequest
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
            'uuid' => ['nullable', Rule::exists('banners', 'uuid')],
            'type' => ['required', Rule::in(array_column(BannerTypes::cases(), 'name'))],
            'picture' => ['nullable', 'image'],
            'title' => ['required', 'string'],
            'body' => ['nullable', 'string'],
            'color_scheme' => ['required', 'string', 'in:light,dark'],
            'button_text' => ['nullable', 'string'],
            'button_url' => ['required_with:button_text', 'string'],
            'start' => ['required', 'date'],
            'end' => ['nullable', 'date', 'after:start'],
            'delay_seconds' => ['nullable', 'min:0'],
            'not_disturb_hours' => ['nullable', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
