<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Support\Collection;

class Setting extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['value'];

    /**
     *
     */
    public function getValueAttribute()
    {
        $value = $this->attributes['value'];

        if (!empty($value)) {
            $value = json_decode($value);
            if (json_last_error()) {
                return $this->attributes['value'];
            }
        }

        return $value;
    }


    public static function getByKey(string $key)
    {
        return Setting::where('key', $key)->first();
    }

    /**
     * @param string $key
     * @param string|null $value
     * @return self
     */
    public static function updateValue(string $key, string $value = null): Setting
    {
        $setting = Setting::getByKey($key);
        if (!$setting) {
            $setting = new Setting();
            $setting->key = $key;
        }

        if (!is_string($value)) $value = json_encode($value);

        $setting->fill([ 'value' => $value ])->save();
        return $setting;
    }

    /**
     * @return Collection
     */
    public static function getAll(): Collection
    {
        return Setting::all()->pluck('value', 'key');
    }
}
