<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Penerima;
use App\Models\Pengajuan;
use App\Models\RiwayatStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersetujuanTest extends TestCase
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
     * Test index access controls.
     */
    public function test_only_pimpinan_and_super_admin_can_access_index(): void
    {
        // Petugas should get 403 Forbidden
        $response = $this->actingAs($this->petugas)->get(route('persetujuan.index'));
        $response->assertStatus(403);

        // Admin should get 403 Forbidden
        $response = $this->actingAs($this->admin)->get(route('persetujuan.index'));
        $response->assertStatus(403);

        // Pimpinan should get 200 OK
        $response = $this->actingAs($this->pimpinan)->get(route('persetujuan.index'));
        $response->assertStatus(200);

        // Super Admin should get 200 OK
        $response = $this->actingAs($this->superAdmin)->get(route('persetujuan.index'));
        $response->assertStatus(200);
    }

    /**
     * Test only Pimpinan can approve, Super Admin cannot approve (forbidden by FormRequest authorize).
     */
    public function test_only_pimpinan_can_submit_approval(): void
    {
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'PGJ-20260708-0001',
            'penerima_id' => $this->penerima->id,
            'petugas_id' => $this->petugas->id,
            'tanggal_pengajuan' => today(),
            'status' => 'menunggu_persetujuan',
        ]);

        // Super Admin gets 403 on POST route
        $response = $this->actingAs($this->superAdmin)->post(route('persetujuan.approve', $pengajuan), [
            'keputusan' => 'setujui',
            'catatan' => 'OK',
        ]);
        $response->assertStatus(403);
    }

    /**
     * Test cannot approve if status is not menunggu_persetujuan.
     */
    public function test_cannot_approve_if_status_is_not_menunggu_persetujuan(): void
    {
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'PGJ-20260708-0001',
            'penerima_id' => $this->penerima->id,
            'petugas_id' => $this->petugas->id,
            'tanggal_pengajuan' => today(),
            'status' => 'menunggu_verifikasi',
        ]);

        $response = $this->actingAs($this->pimpinan)->post(route('persetujuan.approve', $pengajuan), [
            'keputusan' => 'setujui',
            'catatan' => 'OK',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals('menunggu_verifikasi', $pengajuan->fresh()->status);
    }

    /**
     * Test approving works.
     */
    public function test_approve_setujui_decision(): void
    {
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'PGJ-20260708-0001',
            'penerima_id' => $this->penerima->id,
            'petugas_id' => $this->petugas->id,
            'tanggal_pengajuan' => today(),
            'status' => 'menunggu_persetujuan',
        ]);

        $response = $this->actingAs($this->pimpinan)->post(route('persetujuan.approve', $pengajuan), [
            'keputusan' => 'setujui',
            'catatan' => 'Sangat layak menerima bantuan',
        ]);

        $response->assertRedirect(route('persetujuan.index'));
        $this->assertEquals('siap_disalurkan', $pengajuan->fresh()->status);

        $this->assertDatabaseHas('riwayat_status', [
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $this->pimpinan->id,
            'status' => 'siap_disalurkan',
            'catatan' => 'Sangat layak menerima bantuan',
        ]);
    }

    /**
     * Test rejection requires notes.
     */
    public function test_approve_tolak_requires_catatan(): void
    {
        $pengajuan = Pengajuan::create([
            'kode_pengajuan' => 'PGJ-20260708-0001',
            'penerima_id' => $this->penerima->id,
            'petugas_id' => $this->petugas->id,
            'tanggal_pengajuan' => today(),
            'status' => 'menunggu_persetujuan',
        ]);

        // Submit without notes
        $response = $this->actingAs($this->pimpinan)->post(route('persetujuan.approve', $pengajuan), [
            'keputusan' => 'tolak',
            'catatan' => '',
        ]);

        $response->assertSessionHasErrors('catatan');
        $this->assertEquals('menunggu_persetujuan', $pengajuan->fresh()->status);

        // Submit with notes
        $response = $this->actingAs($this->pimpinan)->post(route('persetujuan.approve', $pengajuan), [
            'keputusan' => 'tolak',
            'catatan' => 'Penerima tidak masuk kriteria sasaran.',
        ]);

        $response->assertRedirect(route('persetujuan.index'));
        $this->assertEquals('ditolak_pimpinan', $pengajuan->fresh()->status);

        $this->assertDatabaseHas('riwayat_status', [
            'pengajuan_id' => $pengajuan->id,
            'user_id' => $this->pimpinan->id,
            'status' => 'ditolak_pimpinan',
            'catatan' => 'Penerima tidak masuk kriteria sasaran.',
        ]);
    }
}
