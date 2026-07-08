<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Penerima;
use App\Models\Pengajuan;
use App\Models\Survei;
use App\Models\RiwayatStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerifikasiTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $admin;
    private User $petugas;
    private User $pimpinan;
    private Penerima $penerima;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users for each role
        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->petugas = User::factory()->create(['role' => 'petugas']);
        $this->pimpinan = User::factory()->create(['role' => 'pimpinan']);

        // Create Penerima
        $this->penerima = Penerima::create([
            'nik' => '1234567890123456',
            'no_kk' => '1234567890123456',
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Jl. Merdeka No. 10',
            'rt' => '001',
            'rw' => '002',
            'desa' => 'Gambir',
            'kecamatan' => 'Gambir',
            'kabupaten' => 'Jakarta Pusat',
            'provinsi' => 'DKI Jakarta',
            'no_hp' => '081234567890',
        ]);
    }

    /**
     * Test verification index can only be accessed by Super Admin and Admin.
     */
    public function test_only_admin_and_super_admin_can_access_index(): void
    {
        // Petugas should get 403 Forbidden because route is role protected
        $response = $this->actingAs($this->petugas)->get(route('verifikasi.index'));
        $response->assertStatus(403);

        // Pimpinan should get 403 Forbidden
        $response = $this->actingAs($this->pimpinan)->get(route('verifikasi.index'));
        $response->assertStatus(403);

        // Admin should get 200 OK
        $response = $this->actingAs($this->admin)->get(route('verifikasi.index'));
        $response->assertStatus(200);

        // Super Admin should get 200 OK
        $response = $this->actingAs($this->superAdmin)->get(route('verifikasi.index'));
        $response->assertStatus(200);
    }

    /**
     * Test verifying when status is not menunggu_verifikasi fails.
     */
    public function test_cannot_verify_if_status_is_not_menunggu_verifikasi(): void
    {
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'PGJ-20260708-0001',
            'penerima_id' => $this->penerima->id,
            'petugas_id' => $this->petugas->id,
            'tanggal_pengajuan' => today(),
            'status' => 'menunggu_survei', // Status is not menunggu_verifikasi
        ]);

        $response = $this->actingAs($this->admin)->post(route('verifikasi.verify', $pengajuan), [
            'keputusan' => 'setujui',
            'catatan' => 'OK',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals('menunggu_survei', $pengajuan->fresh()->status);
    }

    /**
     * Test verifying 'setujui' works.
     */
    public function test_verify_setujui_decision(): void
    {
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'PGJ-20260708-0001',
            'penerima_id' => $this->penerima->id,
            'petugas_id' => $this->petugas->id,
            'tanggal_pengajuan' => today(),
            'status' => 'menunggu_verifikasi',
        ]);

        // Create associated Survei
        Survei::create([
            'pengajuan_id' => $pengajuan->id,
            'status_rumah' => 'Layak Huni',
            'kepemilikan_rumah' => 'Milik Sendiri',
            'jenis_lantai' => 'Keramik',
            'jenis_dinding' => 'Tembok Bata',
            'jenis_atap' => 'Genteng',
            'jumlah_kamar' => 3,
            'jumlah_penghuni' => 4,
            'pekerjaan' => 'Buruh',
            'penghasilan' => 1500000,
            'jumlah_tanggungan' => 2,
        ]);

        $response = $this->actingAs($this->admin)->post(route('verifikasi.verify', $pengajuan), [
            'keputusan' => 'setujui',
            'catatan' => 'Data survei lengkap dan layak',
        ]);

        $response->assertRedirect(route('verifikasi.index'));
        $response->assertSessionHas('success');

        // Check pengajuan status
        $this->assertEquals('menunggu_persetujuan', $pengajuan->fresh()->status);

        // Check riwayat_status is created
        $this->assertDatabaseHas('riwayat_status', [
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $this->admin->id,
            'status' => 'menunggu_persetujuan',
            'catatan' => 'Data survei lengkap dan layak',
        ]);
    }

    /**
     * Test verifying 'revisi' requires notes.
     */
    public function test_verify_revisi_requires_catatan(): void
    {
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'PGJ-20260708-0001',
            'penerima_id' => $this->penerima->id,
            'petugas_id' => $this->petugas->id,
            'tanggal_pengajuan' => today(),
            'status' => 'menunggu_verifikasi',
        ]);

        // Try without notes
        $response = $this->actingAs($this->admin)->post(route('verifikasi.verify', $pengajuan), [
            'keputusan' => 'revisi',
            'catatan' => '',
        ]);

        $response->assertSessionHasErrors('catatan');
        $this->assertEquals('menunggu_verifikasi', $pengajuan->fresh()->status);

        // Try with notes
        $response = $this->actingAs($this->admin)->post(route('verifikasi.verify', $pengajuan), [
            'keputusan' => 'revisi',
            'catatan' => 'Foto dapur belum jelas.',
        ]);

        $response->assertRedirect(route('verifikasi.index'));
        $this->assertEquals('revisi_survei', $pengajuan->fresh()->status);
        $this->assertDatabaseHas('riwayat_status', [
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $this->admin->id,
            'status' => 'revisi_survei',
            'catatan' => 'Foto dapur belum jelas.',
        ]);
    }

    /**
     * Test verifying 'tolak' requires notes.
     */
    public function test_verify_tolak_requires_catatan(): void
    {
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'PGJ-20260708-0001',
            'penerima_id' => $this->penerima->id,
            'petugas_id' => $this->petugas->id,
            'tanggal_pengajuan' => today(),
            'status' => 'menunggu_verifikasi',
        ]);

        // Try without notes
        $response = $this->actingAs($this->admin)->post(route('verifikasi.verify', $pengajuan), [
            'keputusan' => 'tolak',
            'catatan' => '',
        ]);

        $response->assertSessionHasErrors('catatan');
        $this->assertEquals('menunggu_verifikasi', $pengajuan->fresh()->status);

        // Try with notes
        $response = $this->actingAs($this->admin)->post(route('verifikasi.verify', $pengajuan), [
            'keputusan' => 'tolak',
            'catatan' => 'Penghasilan di atas batas maksimal.',
        ]);

        $response->assertRedirect(route('verifikasi.index'));
        $this->assertEquals('ditolak_admin', $pengajuan->fresh()->status);
        $this->assertDatabaseHas('riwayat_status', [
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $this->admin->id,
            'status' => 'ditolak_admin',
            'catatan' => 'Penghasilan di atas batas maksimal.',
        ]);
    }
}
