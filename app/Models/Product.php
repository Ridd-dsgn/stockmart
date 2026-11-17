<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use LogsActivity;

    public const UNITS = ['pcs', 'box', 'pak', 'dus', 'botol', 'lusin', 'kg', 'liter'];

    protected $fillable = [
        'code', 'name', 'category_id', 'unit',
        'buy_price', 'sell_price', 'stock', 'min_stock', 'photo',
    ];

    public function category() { return $this->belongsTo(Category::class); }
}