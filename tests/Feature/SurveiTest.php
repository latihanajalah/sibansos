<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Penerima;
use App\Models\Pengajuan;
use App\Models\Survei;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class SurveiTest extends TestCase
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

    public function test_petugas_can_view_survei()
    {
        $petugas = User::factory()->create(['role' => 'petugas', 'status' => 'aktif', 'no_hp' => '0811111111']);
        $penerima = $this->createPenerima();
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'PGJ-' . Carbon::today()->format('Ymd') . '-0001',
            'penerima_id' => $penerima->id,
            'petugas_id' => $petugas->id,
            'tanggal_pengajuan' => Carbon::today(),
            'status' => 'menunggu_verifikasi'
        ]);
        
        $survei = Survei::create([
            'pengajuan_id' => $pengajuan->id,
            'status_rumah' => 'Milik Sendiri',
            'kepemilikan_rumah' => 'Hak Milik',
            'jenis_lantai' => 'Keramik',
            'jenis_dinding' => 'Tembok',
            'jenis_atap' => 'Genting',
            'jumlah_kamar' => 2,
            'jumlah_penghuni' => 4,
            'pekerjaan' => 'Buruh',
            'penghasilan' => 1500000,
            'jumlah_tanggungan' => 3,
            'punya_motor' => true,
            'punya_mobil' => false,
            'punya_sawah' => false,
            'punya_ternak' => false,
            'catatan' => 'Layak',
        ]);

        $response = $this->actingAs($petugas)->get('/survei/' . $survei->id);

        $response->assertStatus(200);
        $response->assertSee($pengajuan->kode_pengajuan);
    }
}
