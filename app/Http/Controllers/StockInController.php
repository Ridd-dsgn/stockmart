<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockIn;
use App\Models\StockInItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        return view('stock-ins.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'proof_file' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:2048',
            ],
            'items' => ['required', 'array', 'min:1'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $proofPath = null;

        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')
                ->store('stock-in-proofs', 'public');
        }

        try {
            DB::transaction(function () use ($validated, $proofPath) {
                // Cari supplier berdasarkan nama.
                $supplierName = trim($validated['supplier_name']);

                $supplier = Supplier::whereRaw(
                    'LOWER(name) = ?',
                    [strtolower($supplierName)]
                )->first();

                // Jika belum ada, buat supplier baru.
                if (!$supplier) {
                    $supplier = Supplier::create([
                        'name' => $supplierName,
                    ]);
                }

                $stockIn = StockIn::create([
                    'supplier_id' => $supplier->id,
                    'user_id' => auth()->id(),
                    'date' => $validated['date'],
                    'proof_file' => $proofPath,
                    'note' => $validated['note'] ?? null,
                ]);

                // Kategori otomatis untuk barang baru.
                $category = Category::firstOrCreate(
                    ['name' => 'Lainnya'],
                    ['description' => 'Kategori otomatis untuk barang baru.']
                );

                foreach ($validated['items'] as $item) {
                    $name = trim($item['name']);

                    // Cari barang berdasarkan nama.
                    $product = Product::whereRaw(
                        'LOWER(name) = ?',
                        [strtolower($name)]
                    )->lockForUpdate()->first();

                    // Buat barang baru jika belum terdaftar.
                    if (!$product) {
                        do {
                            $code = 'BRG-' . Str::upper(Str::random(8));
                        } while (Product::where('code', $code)->exists());

                        $product = Product::create([
                            'code' => $code,
                            'name' => $name,
                            'category_id' => $category->id,
                            'unit' => 'pcs',
                            'buy_price' => 0,
                            'sell_price' => 0,
                            'stock' => 0,
                            'min_stock' => 0,
                        ]);
                    }

                    // Tambahkan stok barang.
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
