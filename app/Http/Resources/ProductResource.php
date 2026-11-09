<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'code'       => $this->code,
            'name'       => $this->name,
            'category'   => $this->whenLoaded('category', fn () => $this->category->name),
            'unit'       => $this->unit,
            'buy_price'  => $this->buy_price,
            'sell_price' => $this->sell_price,
            'stock'      => $this->stock,
            'min_stock'  => $this->min_stock,
            'status'     => match (true) {
                $this->stock <= 0                    => 'habis',
                $this->stock <= $this->min_stock     => 'menipis',
                default                              => 'aman',
            },
            'photo_url'  => $this->photo ? asset('storage/' . $this->photo) : null,
        ];
    }
}