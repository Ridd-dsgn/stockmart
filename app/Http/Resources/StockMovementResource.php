<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'product'     => $this->whenLoaded('product', fn () => [
                'id'   => $this->product->id,
                'code' => $this->product->code,
                'name' => $this->product->name,
            ]),
            'type'        => $this->type,
            'qty'         => $this->qty,
            'stock_after' => $this->stock_after,
            'created_at'  => $this->created_at,
        ];
    }
}