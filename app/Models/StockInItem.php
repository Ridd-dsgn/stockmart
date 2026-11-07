<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockInItem extends Model
{
    protected $fillable = ['stock_in_id', 'product_id', 'qty'];
}
