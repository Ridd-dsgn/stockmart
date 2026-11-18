<?php

namespace App\Models;

use App\Models\Concerns\LogsActivity;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'phone', 'email', 'address'];

    public function stockIns() { return $this->hasMany(StockIn::class); }
}