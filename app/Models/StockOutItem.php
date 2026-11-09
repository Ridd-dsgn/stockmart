<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOutItem extends Model
{
    protected $fillable = ['stock_out_id', 'product_id', 'qty'];
}
