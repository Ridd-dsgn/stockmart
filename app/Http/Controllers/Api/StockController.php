<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StockMovementResource;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockController extends Controller
{
    // GET /api/stocks?status=habis|menipis
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->status === 'habis', fn ($q) => $q->where('stock', 0))
            ->when($request->status === 'menipis', fn ($q) => $q->where('stock', '>', 0)->whereColumn('stock', '<=', 'min_stock'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return ProductResource::collection($products);
    }

    // GET /api/stock-movements?product_id=1
    public function movements(Request $request)
    {
        $movements = StockMovement::with('product:id,code,name')
            ->when($request->product_id, fn ($q, $id) => $q->where('product_id', $id))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return StockMovementResource::collection($movements);
    }
}