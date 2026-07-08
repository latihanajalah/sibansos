<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Penerima;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenerimaTest extends TestCase
{
    use RefreshDatabase;

    private function createPenerima()
    {
        return Penerima::create([
            'nik' => '1234567890123456',
            'no_kk' => '6543210987654321',
            'nama' => 'Budi Santoso',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Merdeka No. 1',
            'rt' => '001',
            'rw' => '002',
            'desa' => 'Desa A',
            'kecamatan' => 'Kecamatan B',
            'kabupaten' => 'Kota C',
            'provinsi' => 'Provinsi D',
            'no_hp' => '08123456789',
        ]);
    }

    public function test_admin_can_view_penerima()
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif', 'no_hp' => '0811111111']);
        $penerima = $this->createPenerima();

        $response = $this->actingAs($admin)->get('/master/penerima');

        $response->assertStatus(200);
        $response->assertSee($penerima->nama);
    }

    public function test_admin_can_create_penerima()
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif', 'no_hp' => '0811111111']);

        $response = $this->actingAs($admin)->post('/master/penerima', [
            'nik' => '1234567890123457',
            'no_kk' => '6543210987654321',
            'nama' => 'Budi Santoso',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Merdeka No. 1',
            'rt' => '001',
            'rw' => '002',
            'desa' => 'Desa A',
            'kecamatan' => 'Kecamatan B',
            'kabupaten' => 'Kota C',
            'provinsi' => 'Provinsi D',
            'no_hp' => '08123456789',
        ]);

        $response->assertRedirect('/master/penerima');
        $this->assertDatabaseHas('penerima', ['nik' => '1234567890123457']);
    }

    public function test_admin_can_update_penerima()
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif', 'no_hp' => '0811111111']);
        $penerima = $this->createPenerima();

        $response = $this->actingAs($admin)->put('/master/penerima/' . $penerima->id, [
            'nik' => '1234567890123456',
            'no_kk' => '6543210987654321',
            'nama' => 'Budi Santoso Edit',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Merdeka No. 1',
            'rt' => '001',
            'rw' => '002',
            'desa' => 'Desa A',
            'kecamatan' => 'Kecamatan B',
            'kabupaten' => 'Kota C',
            'provinsi' => 'Provinsi D',
            'no_hp' => '08123456789',
        ]);

        $response->assertRedirect('/master/penerima');
        $this->assertDatabaseHas('penerima', ['id' => $penerima->id, 'nama' => 'Budi Santoso Edit']);
    }

    public function test_admin_can_delete_penerima()
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'aktif', 'no_hp' => '0811111111']);
        $penerima = $this->createPenerima();

        $response = $this->actingAs($admin)->delete('/master/penerima/' . $penerima->id);

        $response->assertRedirect('/master/penerima');
        $this->assertSoftDeleted('penerima', ['id' => $penerima->id]);
    }
}
