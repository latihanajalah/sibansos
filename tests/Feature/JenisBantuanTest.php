<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\JenisBantuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisBantuanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_jenis_bantuan()
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif', 'no_hp' => '0811111111']);
        $jenisBantuan = JenisBantuan::create(['kode' => 'JB-123', 'nama_bantuan' => 'Beras', 'keterangan' => 'Beras', 'status' => true]);

        $response = $this->actingAs($admin)->get('/master/jenis-bantuan');

        $response->assertStatus(200);
        $response->assertSee($jenisBantuan->nama_bantuan);
    }

    public function test_petugas_cannot_view_jenis_bantuan()
    {
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif', 'no_hp' => '0811111111']);

        $response = $this->actingAs($petugas)->get('/master/jenis-bantuan');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_jenis_bantuan()
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif', 'no_hp' => '0811111111']);

        $response = $this->actingAs($admin)->post('/master/jenis-bantuan', [
            'kode' => 'JB-001',
            'nama_bantuan' => 'Bantuan Tunai',
            'deskripsi' => 'Bantuan Tunai Langsung',
            'status' => '1',
        ]);

        $response->assertRedirect('/master/jenis-bantuan');
        $this->assertDatabaseHas('jenis_bantuan', ['kode' => 'JB-001']);
    }

    public function test_admin_can_update_jenis_bantuan()
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif', 'no_hp' => '0811111111']);
        $jenisBantuan = JenisBantuan::create(['kode' => 'JB-123', 'nama_bantuan' => 'Beras', 'deskripsi' => 'Beras', 'status' => true]);

        $response = $this->actingAs($admin)->put('/master/jenis-bantuan/' . $jenisBantuan->id, [
            'kode' => 'JB-002',
            'nama_bantuan' => 'Bantuan Beras',
            'deskripsi' => 'Bantuan Sembako',
            'status' => '0',
        ]);

        $response->assertRedirect('/master/jenis-bantuan');
        $this->assertDatabaseHas('jenis_bantuan', ['id' => $jenisBantuan->id, 'kode' => 'JB-002', 'status' => false]);
    }

    public function test_super_admin_can_delete_jenis_bantuan()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin', 'status' => 'aktif', 'no_hp' => '0811111111']);
        $jenisBantuan = JenisBantuan::create(['kode' => 'JB-123', 'nama_bantuan' => 'Beras', 'deskripsi' => 'Beras', 'status' => true]);

        $response = $this->actingAs($superAdmin)->delete('/master/jenis-bantuan/' . $jenisBantuan->id);

        $response->assertRedirect('/master/jenis-bantuan');
        $this->assertSoftDeleted('jenis_bantuan', ['id' => $jenisBantuan->id]);
    }
}
