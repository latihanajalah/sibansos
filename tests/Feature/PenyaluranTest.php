<?php

namespace Tests\Feature;

use App\Models\Dokumen;
use App\Models\Pengajuan;
use App\Models\Penerima;
use App\Models\Penyaluran;
use App\Models\RiwayatStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PenyaluranTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $admin;
    private User $petugas;
    private User $pimpinan;
    private Penerima $penerima;
    private Pengajuan $pengajuan;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->superAdmin = User::factory()->create(['role' => 'super_admin']);
        $this->admin      = User::factory()->create(['role' => 'admin']);
        $this->petugas    = User::factory()->create(['role' => 'petugas']);
        $this->pimpinan   = User::factory()->create(['role' => 'pimpinan']);

        $this->penerima = Penerima::create([
            'nik'          => '1234567890123456',
            'no_kk'        => '1234567890123456',
            'nama'         => 'Budi Santoso',
            'jenis_kelamin'=> 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir'=> '1990-01-01',
            'alamat'       => 'Jl. Merdeka No. 10',
            'rt'           => '001',
            'rw'           => '002',
            'desa'         => 'Gambir',
            'kecamatan'    => 'Gambir',
            'kabupaten'    => 'Jakarta Pusat',
            'provinsi'     => 'DKI Jakarta',
            'no_hp'        => '081234567890',
        ]);

        $this->pengajuan = Pengajuan::create([
            'kode_pengajuan'   => 'PGJ-20260708-0001',
            'penerima_id'      => $this->penerima->id,
            'petugas_id'       => $this->petugas->id,
            'tanggal_pengajuan'=> today(),
            'status'           => 'siap_disalurkan',
        ]);
    }

    // ─── ACCESS CONTROL ────────────────────────────────────────

    public function test_pimpinan_can_view_penyaluran_index(): void
    {
        $response = $this->actingAs($this->pimpinan)->get(route('penyaluran.index'));
        $response->assertStatus(200);
    }

    public function test_super_admin_and_admin_can_view_penyaluran_index(): void
    {
        $this->actingAs($this->superAdmin)->get(route('penyaluran.index'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('penyaluran.index'))->assertStatus(200);
    }

    public function test_pimpinan_cannot_access_create_form(): void
    {
        // Pimpinan is blocked by middleware (role:super_admin,admin,petugas)
        // They receive 403 Forbidden from the role middleware
        $response = $this->actingAs($this->pimpinan)
            ->get(route('penyaluran.create') . '?pengajuan_id=' . $this->pengajuan->id);
        $response->assertStatus(403);
    }

    public function test_petugas_can_access_create_form(): void
    {
        $response = $this->actingAs($this->petugas)
            ->get(route('penyaluran.create') . '?pengajuan_id=' . $this->pengajuan->id);
        $response->assertStatus(200);
    }

    // ─── GUARD: STATUS CHECK ────────────────────────────────────

    public function test_cannot_create_penyaluran_if_status_is_not_siap_disalurkan(): void
    {
        $this->pengajuan->update(['status' => 'menunggu_verifikasi']);

        $response = $this->actingAs($this->petugas)
            ->get(route('penyaluran.create') . '?pengajuan_id=' . $this->pengajuan->id);

        // Controller redirects to pengajuan.show with error flash
        $response->assertRedirect(route('pengajuan.show', $this->pengajuan));
        $response->assertSessionHas('error');
    }

    public function test_store_rejected_if_status_is_not_siap_disalurkan(): void
    {
        $this->pengajuan->update(['status' => 'menunggu_persetujuan']);

        $file = UploadedFile::fake()->create('bukti.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->petugas)->post(route('penyaluran.store'), [
            'pengajuan_id' => $this->pengajuan->id,
            'tanggal'      => today()->format('Y-m-d'),
            'bukti'        => [$file],
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals('menunggu_persetujuan', $this->pengajuan->fresh()->status);
    }

    // ─── SUCCESSFUL STORE ───────────────────────────────────────

    public function test_petugas_can_store_penyaluran_with_bukti(): void
    {
        $file = UploadedFile::fake()->create('bukti-penyerahan.pdf', 500, 'application/pdf');

        $response = $this->actingAs($this->petugas)->post(route('penyaluran.store'), [
            'pengajuan_id' => $this->pengajuan->id,
            'tanggal'      => today()->format('Y-m-d'),
            'catatan'      => 'Bantuan diterima dengan baik.',
            'bukti'        => [$file],
        ]);

        $response->assertRedirect(route('penyaluran.index'));
        $response->assertSessionHas('success');

        // Status pengajuan berubah jadi selesai
        $this->assertEquals('selesai', $this->pengajuan->fresh()->status);

        // Data penyaluran tersimpan
        $this->assertDatabaseHas('penyaluran', [
            'pengajuan_id' => $this->pengajuan->id,
            'petugas_id'   => $this->petugas->id,
            'status'       => 'selesai',
            'catatan'      => 'Bantuan diterima dengan baik.',
        ]);

        // Riwayat status dibuat
        $this->assertDatabaseHas('riwayat_status', [
            'pengajuan_id' => $this->pengajuan->id,
            'user_id'      => $this->petugas->id,
            'status'       => 'selesai',
            'catatan'      => 'Bantuan telah disalurkan.',
        ]);

        // Dokumen bukti tersimpan
        $this->assertDatabaseHas('dokumen', [
            'pengajuan_id'  => $this->pengajuan->id,
            'nama_dokumen'  => 'Bukti Penyaluran',
        ]);
    }

    public function test_store_requires_at_least_one_bukti_file(): void
    {
        $response = $this->actingAs($this->petugas)->post(route('penyaluran.store'), [
            'pengajuan_id' => $this->pengajuan->id,
            'tanggal'      => today()->format('Y-m-d'),
            'bukti'        => [],
        ]);

        $response->assertSessionHasErrors('bukti');
        $this->assertEquals('siap_disalurkan', $this->pengajuan->fresh()->status);
    }

    public function test_only_petugas_can_submit_penyaluran(): void
    {
        $file = UploadedFile::fake()->create('bukti.jpg', 200, 'image/jpeg');

        // Admin cannot submit (FormRequest authorize() returns false for non-petugas)
        $response = $this->actingAs($this->admin)->post(route('penyaluran.store'), [
            'pengajuan_id' => $this->pengajuan->id,
            'tanggal'      => today()->format('Y-m-d'),
            'bukti'        => [$file],
        ]);
        $response->assertStatus(403);
    }

    // ─── SHOW ───────────────────────────────────────────────────

    public function test_petugas_can_view_own_penyaluran(): void
    {
        $penyaluran = Penyaluran::create([
            'pengajuan_id' => $this->pengajuan->id,
            'petugas_id'   => $this->petugas->id,
            'tanggal'      => today(),
            'status'       => 'selesai',
        ]);

        $response = $this->actingAs($this->petugas)->get(route('penyaluran.show', $penyaluran));
        $response->assertStatus(200);
    }

    public function test_petugas_cannot_view_other_petugas_penyaluran(): void
    {
        $otherPetugas = User::factory()->create(['role' => 'petugas']);
        $penyaluran = Penyaluran::create([
            'pengajuan_id' => $this->pengajuan->id,
            'petugas_id'   => $otherPetugas->id,
            'tanggal'      => today(),
            'status'       => 'selesai',
        ]);

        $response = $this->actingAs($this->petugas)->get(route('penyaluran.show', $penyaluran));
        $response->assertStatus(403);
    }
}
