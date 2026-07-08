<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_users_list()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $user = User::factory()->create();

        $response = $this->actingAs($superAdmin)->get('/super-admin/users');

        $response->assertStatus(200);
        $response->assertSee($user->nama);
    }

    public function test_admin_cannot_view_users_list()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/super-admin/users');

        $response->assertStatus(403);
    }

    public function test_super_admin_can_create_user()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin', 'status' => 'aktif', 'no_hp' => '0811111111']);

        $response = $this->actingAs($superAdmin)->post('/super-admin/users', [
            'nama' => 'Test User Baru',
            'email' => 'testbaru@example.com',
            'role' => 'admin',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'no_hp' => '08123456789',
            'status' => 'aktif',
        ]);

        $response->assertRedirect('/super-admin/users');
        $this->assertDatabaseHas('users', ['email' => 'testbaru@example.com']);
    }

    public function test_super_admin_can_update_user()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin', 'status' => 'aktif', 'no_hp' => '0811111111']);
        $user = User::factory()->create(['status' => 'aktif', 'no_hp' => '0822222222']);

        $response = $this->actingAs($superAdmin)->put('/super-admin/users/' . $user->id, [
            'nama' => 'Nama Update',
            'email' => $user->email,
            'role' => 'petugas',
            'no_hp' => '08123456789',
            'status' => 'aktif',
        ]);

        $response->assertRedirect('/super-admin/users');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'nama' => 'Nama Update', 'role' => 'petugas']);
    }

    public function test_super_admin_can_delete_user()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin', 'status' => 'aktif', 'no_hp' => '0811111111']);
        $user = User::factory()->create(['status' => 'aktif', 'no_hp' => '0822222222']);

        $response = $this->actingAs($superAdmin)->delete('/super-admin/users/' . $user->id);

        $response->assertRedirect('/super-admin/users');
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}
