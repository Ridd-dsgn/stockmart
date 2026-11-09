<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // GET /api/transactions?type=in|out
    public function index(Request $request)
    {
        $type = $request->query('type', 'in');

        abort_unless(in_array($type, ['in', 'out']), 422, 'Parameter type harus "in" atau "out".');

        $query = $type === 'in'
            ? StockIn::with(['supplier:id,name', 'user:id,name', 'items.product:id,code,name'])
            : StockOut::with(['user:id,name', 'items.product:id,code,name']);

        return response()->json(
            $query->latest('date')->latest('id')->paginate(15)->withQueryString()
        );
    }
}