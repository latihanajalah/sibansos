<?php

namespace Tests\Feature;

use App\Models\Dokumen;
use App\Models\JenisBantuan;
use App\Models\Pengajuan;
use App\Models\Penerima;
use App\Models\Penyaluran;
use App\Models\RiwayatStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    private Penerima $penerima;
    private Pengajuan $pengajuan;
    private User $petugas;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        $this->petugas = User::factory()->create(['role' => 'petugas']);

        $this->penerima = Penerima::create([
            'nik'          => '3275110101900001',
            'no_kk'        => '3275110101900001',
            'nama'         => 'Siti Aminah',
            'jenis_kelamin'=> 'P',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir'=> '1990-01-01',
            'alamat'       => 'Jl. Merdeka No. 1',
            'rt'           => '001',
            'rw'           => '001',
            'desa'         => 'Sukajadi',
            'kecamatan'    => 'Sukajadi',
            'kabupaten'    => 'Bandung',
            'provinsi'     => 'Jawa Barat',
            'no_hp'        => '081234567890',
        ]);

        $this->pengajuan = Pengajuan::create([
            'kode_pengajuan'   => 'PGJ-20260708-0001',
            'penerima_id'      => $this->penerima->id,
            'petugas_id'       => $this->petugas->id,
            'tanggal_pengajuan'=> today(),
            'status'           => 'menunggu_survei',
        ]);
    }

    // ─── LANDING PAGE ──────────────────────────────────────────

    public function test_landing_page_accessible_without_auth(): void
    {
        $response = $this->get(route('landing'));
        $response->assertStatus(200);
        $response->assertSee('Sistem Informasi');
        $response->assertSee('Cek Status');
    }

    public function test_landing_page_shows_active_jenis_bantuan(): void
    {
        JenisBantuan::create(['kode' => 'SEMBAKO', 'nama_bantuan' => 'Sembako', 'status' => true]);
        JenisBantuan::create(['kode' => 'NONAKTIF', 'nama_bantuan' => 'Tidak Aktif', 'status' => false]);

        $response = $this->get(route('landing'));
        $response->assertSee('Sembako');
        $response->assertDontSee('Tidak Aktif');
    }

    // ─── CEK STATUS FORM ───────────────────────────────────────

    public function test_cek_status_page_accessible_without_auth(): void
    {
        $response = $this->get(route('cek-status'));
        $response->assertStatus(200);
        $response->assertSee('Cek Status Bantuan');
    }

    // ─── STATUS (HASIL PENCARIAN) ──────────────────────────────

    public function test_status_shows_empty_state_for_unknown_nik(): void
    {
        $response = $this->get(route('status', ['nik' => '9999999999999999']));
        $response->assertStatus(200);
        $response->assertSee('Data Tidak Ditemukan');
        $response->assertSee('Data pengajuan tidak ditemukan');
    }

    public function test_status_shows_validation_error_for_invalid_nik(): void
    {
        // Non-numeric
        $response = $this->get(route('status', ['nik' => 'ABCDEFGHIJKLMNOP']));
        $response->assertSessionHasErrors('nik');

        // Too short
        $response2 = $this->get(route('status', ['nik' => '12345']));
        $response2->assertSessionHasErrors('nik');
    }

    public function test_status_shows_penerima_data_with_masked_nik(): void
    {
        $response = $this->get(route('status', ['nik' => $this->penerima->nik]));

        $response->assertStatus(200);
        $response->assertSee('Siti Aminah');
        $response->assertSee('0001');             // last 4 digits of NIK appear in masked version
        $response->assertSee('************0001'); // masked NIK format shown in penerima card
        $response->assertSee('Sukajadi');
        $response->assertSee('Bandung');
    }

    public function test_status_shows_pengajuan_data(): void
    {
        $response = $this->get(route('status', ['nik' => $this->penerima->nik]));

        $response->assertSee('PGJ-20260708-0001');
        $response->assertSee($this->petugas->name);
    }

    public function test_status_shows_current_status_badge(): void
    {
        $response = $this->get(route('status', ['nik' => $this->penerima->nik]));
        $response->assertSee('Menunggu Survei');
    }

    public function test_status_shows_riwayat_status_timeline(): void
    {
        RiwayatStatus::create([
            'pengajuan_id' => $this->pengajuan->id,
            'user_id'      => $this->petugas->id,
            'status'       => 'menunggu_survei',
            'catatan'      => 'Pengajuan diterima oleh sistem.',
        ]);

        $response = $this->get(route('status', ['nik' => $this->penerima->nik]));
        $response->assertSee('Pengajuan diterima oleh sistem.');
        $response->assertSee($this->petugas->name);
    }

    public function test_status_shows_jenis_bantuan(): void
    {
        $jb = JenisBantuan::create(['kode' => 'SEMBAKO', 'nama_bantuan' => 'Sembako Murah', 'status' => true]);
        $this->pengajuan->jenisBantuan()->attach($jb->id);

        $response = $this->get(route('status', ['nik' => $this->penerima->nik]));
        $response->assertSee('Sembako Murah');
    }

    public function test_status_shows_penyaluran_info_when_selesai(): void
    {
        $this->pengajuan->update(['status' => 'selesai']);

        Penyaluran::create([
            'pengajuan_id' => $this->pengajuan->id,
            'petugas_id'   => $this->petugas->id,
            'tanggal'      => today(),
            'status'       => 'selesai',
            'catatan'      => 'Bantuan telah disalurkan.',
        ]);

        $response = $this->get(route('status', ['nik' => $this->penerima->nik]));

        $response->assertSee('Informasi Penyaluran');
        $response->assertSee(today()->format('d M Y'));
        $response->assertSee('Bantuan Telah Disalurkan');
    }

    public function test_status_shows_bukti_download_when_selesai(): void
    {
        $this->pengajuan->update(['status' => 'selesai']);

        Penyaluran::create([
            'pengajuan_id' => $this->pengajuan->id,
            'petugas_id'   => $this->petugas->id,
            'tanggal'      => today(),
            'status'       => 'selesai',
        ]);

        Dokumen::create([
            'pengajuan_id' => $this->pengajuan->id,
            'nama_dokumen' => 'Bukti Penyaluran',
            'file'         => 'dokumen/fake-bukti.pdf',
        ]);

        $response = $this->get(route('status', ['nik' => $this->penerima->nik]));
        $response->assertSee('Bukti Penyaluran');
        $response->assertSee('Download');
    }

    public function test_status_shows_empty_state_for_penerima_without_pengajuan(): void
    {
        // Buat penerima lain tanpa pengajuan
        $lain = Penerima::create([
            'nik'          => '3275110101900002',
            'no_kk'        => '3275110101900002',
            'nama'         => 'Budi Tanpa Pengajuan',
            'jenis_kelamin'=> 'L',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir'=> '1985-01-01',
            'alamat'       => 'Jl. Test',
            'rt'           => '001',
            'rw'           => '001',
            'desa'         => 'Desa',
            'kecamatan'    => 'Kecamatan',
            'kabupaten'    => 'Kabupaten',
            'provinsi'     => 'Provinsi',
            'no_hp'        => '082100000000',
        ]);

        $response = $this->get(route('status', ['nik' => $lain->nik]));
        $response->assertSee('Data Tidak Ditemukan');
    }
}
