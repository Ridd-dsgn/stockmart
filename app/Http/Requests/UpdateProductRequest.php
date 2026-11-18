<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // akses dijaga middleware role:admin di route
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'string', 'max:50', Rule::unique('products', 'code')->ignore($this->route('product'))],
            'name'        => ['required', 'string', 'max:150'],
            'category_id' => ['required', 'exists:categories,id'],
            'unit'        => ['required', Rule::in(Product::UNITS)],
            'buy_price'   => ['required', 'integer', 'min:0'],
            'sell_price'  => ['required', 'integer', 'min:0', 'gte:buy_price'],
            'min_stock'   => ['required', 'integer', 'min:0'],
            'photo'       => ['nullable', 'image', 'max:2048'],
        ];
    }
}