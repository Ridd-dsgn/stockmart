<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')
            ->when($request->q, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when($request->status === 'low', function ($query) {
                $query->whereColumn('stock', '<=', 'min_stock');
            })
            ->when($request->status === 'safe', function ($query) {
                $query->whereColumn('stock', '>', 'min_stock');
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalProducts = Product::count();

        $lowStockCount = Product::whereColumn(
            'stock',
            '<=',
            'min_stock'
        )->count();

        $safeStockCount = $totalProducts - $lowStockCount;

        return view('stocks.index', compact(
            'products',
            'totalProducts',
            'lowStockCount',
            'safeStockCount'
        ));
    }
}
