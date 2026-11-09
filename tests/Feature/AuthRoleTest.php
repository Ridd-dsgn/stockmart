<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', ['email' => $user->email, 'password' => 'salah']);

        $this->assertGuest();
    }

    public function test_admin_can_open_user_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get('/users')->assertOk();
    }

    public function test_warehouse_staff_cannot_open_user_management(): void
    {
        $staff = User::factory()->create(['role' => 'petugas_gudang']);

        $this->actingAs($staff)->get('/users')->assertForbidden();
    }

    public function test_store_manager_cannot_open_activity_log(): void
    {
        $manager = User::factory()->create(['role' => 'kepala_toko']);

        $this->actingAs($manager)->get('/activity-logs')->assertForbidden();
    }

    public function test_admin_can_create_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post('/users', [
            'name'                  => 'Petugas Baru',
            'email'                 => 'baru@stockmart.test',
            'role'                  => 'petugas_gudang',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', ['email' => 'baru@stockmart.test', 'role' => 'petugas_gudang']);
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->delete("/users/{$admin->id}");

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }
}