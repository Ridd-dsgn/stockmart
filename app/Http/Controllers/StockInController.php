<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class StockInController extends Controller
{
    public function index(Request $request)
    {
        $stockIns = StockIn::with(['supplier', 'user', 'items.product'])
            ->when($request->q, function ($query, $search) {
                $query->whereHas('supplier', function ($supplier) use ($search) {
                    $supplier->where('name', 'like', "%{$search}%");
                });
            })
            ->latest('date')
            ->paginate(10)
            ->withQueryString();

        return view('stock-ins.index', compact('stockIns'));
    }

    public function create()
    {
        return view('stock-ins.create', [
            'suppliers' => Supplier::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'proof_file' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => [
                'required',
                'distinct',
                'exists:products,id',
            ],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $proofPath = null;

        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')
                ->store('stock-in-proofs', 'public');
        }

        try {
            DB::transaction(function () use ($validated, $proofPath) {
                $stockIn = StockIn::create([
                    'supplier_id' => $validated['supplier_id'],
                    'user_id' => auth()->id(),
                    'date' => $validated['date'],
                    'proof_file' => $proofPath,
                    'note' => $validated['note'] ?? null,
                ]);

                foreach ($validated['items'] as $item) {
                    $product = Product::whereKey($item['product_id'])
                        ->lockForUpdate()
                        ->firstOrFail();

                    $product->increment('stock', $item['qty']);
                    $product->refresh();

                    StockInItem::create([
                        'stock_in_id' => $stockIn->id,
                        'product_id' => $product->id,
                        'qty' => $item['qty'],
                    ]);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'in',
                        'qty' => $item['qty'],
                        'stock_after' => $product->stock,
                        'reference_type' => StockIn::class,
                        'reference_id' => $stockIn->id,
                    ]);
                }

                ActivityLog::record(
                    'stock_in.created',
                    "Transaksi barang masuk #{$stockIn->id} berhasil dibuat."
                );
            });
        } catch (\Throwable $e) {
            if ($proofPath) {
                Storage::disk('public')->delete($proofPath);
            }

            throw $e;
        }

        return redirect()
            ->route('stock-ins.index')
            ->with('status', 'Transaksi barang masuk berhasil disimpan.');
    }
}
