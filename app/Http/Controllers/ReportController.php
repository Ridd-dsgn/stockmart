<?php

namespace App\Http\Controllers;

use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ]);

        $startDate = $validated['start_date'] ?? null;
        $endDate = $validated['end_date'] ?? null;

        $stockIns = StockIn::with([
                'supplier',
                'user',
                'items.product',
            ])
            ->withSum('items as total_qty', 'qty')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('date', '<=', $endDate);
            })
            ->orderByDesc('date')
            ->get();

        $stockOuts = StockOut::with([
                'user',
                'items.product',
            ])
            ->withSum('items as total_qty', 'qty')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('date', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('date', '<=', $endDate);
            })
            ->orderByDesc('date')
            ->get();

        $totalStockIn = $stockIns->sum('total_qty');
        $totalStockOut = $stockOuts->sum('total_qty');

        return view('reports.index', compact(
            'stockIns',
            'stockOuts',
            'totalStockIn',
            'totalStockOut',
            'startDate',
            'endDate'
        ));
    }
}
