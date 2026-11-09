<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->q, fn ($query, $s) => $query->where(
                fn ($w) => $w->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%")
            ))
            ->when($request->category_id, fn ($query, $c) => $query->where('category_id', $c))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('category'));
    }
}