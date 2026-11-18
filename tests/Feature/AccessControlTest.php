<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Uji hak akses lintas modul sesuai tabel role di README.
 * Setiap kegagalan menyebut role dan URL yang bermasalah.
 */
class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    private const ROLES = ['admin', 'kepala_toko', 'petugas_gudang'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function statusFor(string $role, string $url): int
    {
        $user = User::factory()->create(['role' => $role]);

        return $this->actingAs($user)->get($url)->getStatusCode();
    }

    private function assertStatusFor(int $expected, string $role, string $url): void
    {
        $this->assertSame(
            $expected,
            $this->statusFor($role, $url),
            "Role {$role} membuka {$url} seharusnya status {$expected}."
        );
    }

    public function test_guest_is_redirected_from_all_protected_pages(): void
    {
        $urls = [
            '/dashboard', '/products', '/categories', '/suppliers', '/stocks',
            '/stock-ins', '/stock-outs', '/users', '/activity-logs', '/profile',
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertRedirect('/login');
        }
    }

    public function test_every_role_can_view_read_only_pages(): void
    {
        $urls = [
            '/dashboard', '/products', '/categories', '/suppliers',
            '/stocks', '/stock-ins', '/stock-outs', '/profile',
        ];

        foreach (self::ROLES as $role) {
            foreach ($urls as $url) {
                $this->assertStatusFor(200, $role, $url);
            }
        }
    }

    public function test_only_admin_can_open_master_data_and_admin_pages(): void
    {
        $urls = [
            '/products/create', '/categories/create', '/suppliers/create',
            '/users', '/users/create', '/activity-logs',
        ];

        foreach ($urls as $url) {
            $this->assertStatusFor(200, 'admin', $url);
            $this->assertStatusFor(403, 'kepala_toko', $url);
            $this->assertStatusFor(403, 'petugas_gudang', $url);
        }
    }

    public function test_only_warehouse_staff_can_open_stock_transaction_forms(): void
    {
        $urls = ['/stock-ins/create', '/stock-outs/create'];

        foreach ($urls as $url) {
            $this->assertStatusFor(200, 'petugas_gudang', $url);
            $this->assertStatusFor(403, 'admin', $url);
            $this->assertStatusFor(403, 'kepala_toko', $url);
        }
    }

    public function test_non_admin_cannot_store_master_data(): void
    {
        foreach (['kepala_toko', 'petugas_gudang'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            foreach (['/products', '/categories', '/suppliers', '/users'] as $url) {
                $status = $this->actingAs($user)->post($url, [])->getStatusCode();

                $this->assertSame(403, $status, "Role {$role} POST {$url} seharusnya 403.");
            }
        }
    }

    public function test_non_warehouse_roles_cannot_store_stock_transactions(): void
    {
        foreach (['admin', 'kepala_toko'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            foreach (['/stock-ins', '/stock-outs'] as $url) {
                $status = $this->actingAs($user)->post($url, [])->getStatusCode();

                $this->assertSame(403, $status, "Role {$role} POST {$url} seharusnya 403.");
            }
        }
    }
}