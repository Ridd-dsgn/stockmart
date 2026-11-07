<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique();
            $t->string('name');
            $t->foreignId('category_id')->constrained()->cascadeOnDelete();
            $t->string('unit');
            $t->unsignedBigInteger('buy_price')->default(0);
            $t->unsignedBigInteger('sell_price')->default(0);
            $t->unsignedInteger('stock')->default(0);
            $t->unsignedInteger('min_stock')->default(0);
            $t->string('photo')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
