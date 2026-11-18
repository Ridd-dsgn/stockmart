<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\StockOut;
use App\Models\StockOutItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StockOutController extends Controller
{
    public function index(Request $request)
    {
        $stockOuts = StockOut::with(['user', 'items.product'])
            ->when($request->q, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('reason', 'like', "%{$search}%")
                        ->orWhere('note', 'like', "%{$search}%");
                });
            })
            ->latest('date')
            ->paginate(10)
            ->withQueryString();

        return view('stock-outs.index', compact('stockOuts'));
    }

    public function create()
    {
        return view('stock-outs.create', [
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                'distinct',
                'exists:products,id',
            ],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated) {
            $stockOut = StockOut::create([
                'user_id' => auth()->id(),
                'date' => $validated['date'],
                'reason' => $validated['reason'],
                'note' => $validated['note'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($item['qty'] > $product->stock) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.",
                    ]);
                }

                $product->decrement('stock', $item['qty']);
                $product->refresh();

                StockOutItem::create([
                    'stock_out_id' => $stockOut->id,
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                ]);

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'out',
                    'qty' => $item['qty'],
                    'stock_after' => $product->stock,
                    'reference_type' => StockOut::class,
                    'reference_id' => $stockOut->id,
                ]);
            }

            ActivityLog::record(
                'stock_out.created',
                "Transaksi barang keluar #{$stockOut->id} berhasil dibuat."
            );
        });

        return redirect()
            ->route('stock-outs.index')
            ->with('status', 'Transaksi barang keluar berhasil disimpan.');
    }
}
