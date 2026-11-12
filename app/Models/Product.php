<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'unit',
        'buy_price',
        'sell_price',
        'stock',
        'min_stock',
        'photo',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockInItems()
    {
        return $this->hasMany(StockInItem::class);
    }
}