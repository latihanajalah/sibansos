<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Penerima;
use App\Models\JenisBantuan;
use App\Models\Pengajuan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class PengajuanTest extends TestCase
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

    public function test_petugas_can_view_pengajuan()
    {
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif', 'no_hp' => '0811111111']);
        $penerima = $this->createPenerima();
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'PGJ-' . Carbon::today()->format('Ymd') . '-0001',
            'penerima_id' => $penerima->id,
            'petugas_id' => $petugas->id,
            'tanggal_pengajuan' => Carbon::today(),
            'status' => 'menunggu_survei'
        ]);

        $response = $this->actingAs($petugas)->get('/pengajuan');

        $response->assertStatus(200);
        $response->assertSee($pengajuan->kode_pengajuan);
    }

    public function test_petugas_can_create_pengajuan()
    {
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif', 'no_hp' => '0811111111']);
        $penerima = $this->createPenerima();
        $jenisBantuan = JenisBantuan::create(['kode' => 'JB-123', 'nama_bantuan' => 'Beras', 'keterangan' => 'Beras', 'status' => true]);

        $response = $this->actingAs($petugas)->post('/pengajuan', [
            'penerima_id' => $penerima->id,
            'jenis_bantuan_ids' => [$jenisBantuan->id],
            'keterangan' => 'Pengajuan Baru',
        ]);

        $response->assertRedirect('/pengajuan');
        $this->assertDatabaseHas('pengajuan', [
            'penerima_id' => $penerima->id,
            'petugas_id' => $petugas->id,
            'keterangan' => 'Pengajuan Baru',
        ]);
    }
}
