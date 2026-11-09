<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_returns_token(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'role']]);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->postJson('/api/login', ['email' => $user->email, 'password' => 'salah'])
            ->assertUnauthorized();
    }

    public function test_products_require_token(): void
    {
        $this->getJson('/api/products')->assertUnauthorized();
    }

    public function test_authenticated_user_can_list_products(): void
    {
        $category = Category::create(['name' => 'Makanan']);
        Product::create([
            'code' => 'BRG-001', 'name' => 'Mie Instan', 'category_id' => $category->id,
            'unit' => 'pcs', 'buy_price' => 2500, 'sell_price' => 3000, 'stock' => 5, 'min_stock' => 10,
        ]);

        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/products')
            ->assertOk()
            ->assertJsonPath('data.0.code', 'BRG-001')
            ->assertJsonPath('data.0.status', 'menipis');
    }

    public function test_stocks_can_be_filtered_by_status(): void
    {
        $category = Category::create(['name' => 'Minuman']);
        Product::create(['code' => 'A', 'name' => 'Habis', 'category_id' => $category->id, 'unit' => 'pcs', 'stock' => 0, 'min_stock' => 5]);
        Product::create(['code' => 'B', 'name' => 'Aman', 'category_id' => $category->id, 'unit' => 'pcs', 'stock' => 50, 'min_stock' => 5]);

        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/stocks?status=habis')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.code', 'A');
    }
}