<?php

namespace Tests\Feature;

use App\Models\JenisBantuan;
use App\Models\Pengajuan;
use App\Models\Penerima;
use App\Models\Penyaluran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaporanTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $admin;
    private User $petugas;
    private User $pimpinan;
    private Penerima $penerima;
    private Pengajuan $pengajuan;
    private Penyaluran $penyaluran;
    private JenisBantuan $jenisBantuan;

    protected function setUp(): void
    {
        parent::setUp();

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

        $this->jenisBantuan = JenisBantuan::create([
            'kode' => 'SEMBAKO',
            'nama_bantuan' => 'Bantuan Sembako',
            'status' => true
        ]);

        $this->pengajuan = Pengajuan::create([
            'kode_pengajuan'   => 'PGJ-20260708-0001',
            'penerima_id'      => $this->penerima->id,
            'petugas_id'       => $this->petugas->id,
            'tanggal_pengajuan'=> today(),
            'status'           => 'selesai',
        ]);
        $this->pengajuan->jenisBantuan()->attach($this->jenisBantuan->id);

        $this->penyaluran = Penyaluran::create([
            'pengajuan_id' => $this->pengajuan->id,
            'petugas_id'   => $this->petugas->id,
            'tanggal'      => today(),
            'status'       => 'selesai',
            'catatan'      => 'Disalurkan',
        ]);
    }

    // ─── ACCESS CONTROL ────────────────────────────────────────

    public function test_super_admin_admin_and_pimpinan_can_view_laporan_dashboard(): void
    {
        $this->actingAs($this->superAdmin)->get(route('laporan.index'))->assertStatus(200);
        $this->actingAs($this->admin)->get(route('laporan.index'))->assertStatus(200);
        $this->actingAs($this->pimpinan)->get(route('laporan.index'))->assertStatus(200);
    }

    public function test_petugas_cannot_view_laporan_dashboard(): void
    {
        $this->actingAs($this->petugas)->get(route('laporan.index'))->assertStatus(403);
    }

    public function test_super_admin_admin_and_pimpinan_can_view_laporan_pengajuan_and_penyaluran(): void
    {
        $this->actingAs($this->admin)->get(route('laporan.pengajuan'))->assertStatus(200);
        $this->actingAs($this->pimpinan)->get(route('laporan.pengajuan'))->assertStatus(200);
        
        $this->actingAs($this->admin)->get(route('laporan.penyaluran'))->assertStatus(200);
        $this->actingAs($this->pimpinan)->get(route('laporan.penyaluran'))->assertStatus(200);
    }

    public function test_petugas_cannot_view_laporan_pengajuan_or_penyaluran(): void
    {
        $this->actingAs($this->petugas)->get(route('laporan.pengajuan'))->assertStatus(403);
        $this->actingAs($this->petugas)->get(route('laporan.penyaluran'))->assertStatus(403);
    }

    // ─── EXPORT PDF ────────────────────────────────────────────

    public function test_can_export_pdf_for_pengajuan(): void
    {
        $response = $this->actingAs($this->admin)->get(route('laporan.export.pdf', 'pengajuan'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_can_export_pdf_for_penyaluran(): void
    {
        $response = $this->actingAs($this->admin)->get(route('laporan.export.pdf', 'penyaluran'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    // ─── EXPORT EXCEL ──────────────────────────────────────────

    public function test_admin_and_super_admin_can_export_excel(): void
    {
        $response = $this->actingAs($this->admin)->get(route('laporan.export.excel', 'pengajuan'));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response2 = $this->actingAs($this->superAdmin)->get(route('laporan.export.excel', 'penyaluran'));
        $response2->assertStatus(200);
        $response2->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_pimpinan_cannot_export_excel(): void
    {
        $response = $this->actingAs($this->pimpinan)->get(route('laporan.export.excel', 'pengajuan'));
        $response->assertStatus(403);

        $response2 = $this->actingAs($this->pimpinan)->get(route('laporan.export.excel', 'penyaluran'));
        $response2->assertStatus(403);
    }
}
