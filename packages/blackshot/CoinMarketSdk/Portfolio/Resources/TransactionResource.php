<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'type' => $this->type->name,
            'transfer_type' => $this->transfer_type?->name,
        ]);
    }
}
