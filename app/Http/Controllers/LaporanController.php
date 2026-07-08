<?php

namespace App\Http\Controllers;

use App\Helpers\StatusHelper;
use App\Models\JenisBantuan;
use App\Models\Penerima;
use App\Models\Pengajuan;
use App\Models\Penyaluran;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\SimpleExcel\SimpleExcelWriter;

class LaporanController extends Controller
{

    // ────────────────────────────────────────────────────────────
    // STATISTIK DASHBOARD
    // ────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        // ── Statistik Card ──────────────────────────────────────
        $totalPenerima = Penerima::count();
        $totalPengajuan = Pengajuan::count();

        $byStatus = Pengajuan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $stats = [
            'total_penerima'       => $totalPenerima,
            'total_pengajuan'      => $totalPengajuan,
            'menunggu_survei'      => $byStatus->get('menunggu_survei', 0),
            'menunggu_verifikasi'  => $byStatus->get('menunggu_verifikasi', 0),
            'revisi_survei'        => $byStatus->get('revisi_survei', 0),
            'menunggu_persetujuan' => $byStatus->get('menunggu_persetujuan', 0),
            'siap_disalurkan'      => $byStatus->get('siap_disalurkan', 0),
            'selesai'              => $byStatus->get('selesai', 0),
            'ditolak'              => ($byStatus->get('ditolak_admin', 0) + $byStatus->get('ditolak_pimpinan', 0)),
        ];

        $isSqlite = DB::connection()->getDriverName() === 'sqlite';

        // ── Grafik Pengajuan per Bulan (12 bulan terakhir) ──────
        if ($isSqlite) {
            $pengajuanPerBulan = Pengajuan::select(
                    DB::raw("strftime('%Y', tanggal_pengajuan) as thn"),
                    DB::raw("CAST(strftime('%m', tanggal_pengajuan) as integer) as bln"),
                    DB::raw('COUNT(*) as total')
                )
                ->where('tanggal_pengajuan', '>=', now()->subMonths(11)->startOfMonth())
                ->groupBy('thn', 'bln')
                ->orderBy('thn')
                ->orderBy('bln')
                ->get();
        } else {
            $pengajuanPerBulan = Pengajuan::select(
                    DB::raw('YEAR(tanggal_pengajuan) as thn'),
                    DB::raw('MONTH(tanggal_pengajuan) as bln'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('tanggal_pengajuan', '>=', now()->subMonths(11)->startOfMonth())
                ->groupBy('thn', 'bln')
                ->orderBy('thn')
                ->orderBy('bln')
                ->get();
        }

        // ── Grafik Penyaluran per Bulan (12 bulan terakhir) ──────
        if ($isSqlite) {
            $penyaluranPerBulan = Penyaluran::select(
                    DB::raw("strftime('%Y', tanggal) as thn"),
                    DB::raw("CAST(strftime('%m', tanggal) as integer) as bln"),
                    DB::raw('COUNT(*) as total')
                )
                ->where('tanggal', '>=', now()->subMonths(11)->startOfMonth())
                ->groupBy('thn', 'bln')
                ->orderBy('thn')
                ->orderBy('bln')
                ->get();
        } else {
            $penyaluranPerBulan = Penyaluran::select(
                    DB::raw('YEAR(tanggal) as thn'),
                    DB::raw('MONTH(tanggal) as bln'),
                    DB::raw('COUNT(*) as total')
                )
                ->where('tanggal', '>=', now()->subMonths(11)->startOfMonth())
                ->groupBy('thn', 'bln')
                ->orderBy('thn')
                ->orderBy('bln')
                ->get();
        }

        // ── Grafik Jenis Bantuan ──────────────────────────────────
        $jenisBantuanGrafik = DB::table('pengajuan_bantuan')
            ->join('jenis_bantuan', 'pengajuan_bantuan.jenis_bantuan_id', '=', 'jenis_bantuan.id')
            ->join('pengajuan', 'pengajuan_bantuan.pengajuan_id', '=', 'pengajuan.id')
            ->whereNull('pengajuan.deleted_at')
            ->select('jenis_bantuan.nama_bantuan', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis_bantuan.id', 'jenis_bantuan.nama_bantuan')
            ->orderByDesc('total')
            ->get();

        // ── Build bulan labels (12 bulan terakhir) ───────────────
        $bulanLabels = [];
        $pgPerBulanMap = [];
        $salurPerBulanMap = [];
        for ($i = 11; $i >= 0; $i--) {
            $tgl = now()->subMonths($i);
            $key = $tgl->year . '-' . $tgl->month;
            $bulanLabels[] = $tgl->translatedFormat('M Y');
            $pgPerBulanMap[$key] = 0;
            $salurPerBulanMap[$key] = 0;
        }
        foreach ($pengajuanPerBulan as $r) {
            $k = $r->thn . '-' . $r->bln;
            if (isset($pgPerBulanMap[$k])) {
                $pgPerBulanMap[$k] = $r->total;
            }
        }
        foreach ($penyaluranPerBulan as $r) {
            $k = $r->thn . '-' . $r->bln;
            if (isset($salurPerBulanMap[$k])) {
                $salurPerBulanMap[$k] = $r->total;
            }
        }

        $chartData = [
            'labels'       => $bulanLabels,
            'pengajuan'    => array_values($pgPerBulanMap),
            'penyaluran'   => array_values($salurPerBulanMap),
            'jenisBantuan' => [
                'labels' => $jenisBantuanGrafik->pluck('nama_bantuan')->toArray(),
                'data'   => $jenisBantuanGrafik->pluck('total')->toArray(),
            ],
            'statusPengajuan' => [
                'labels' => collect($byStatus)->keys()->map(fn ($s) => StatusHelper::label($s)[0])->toArray(),
                'data'   => collect($byStatus)->values()->toArray(),
            ],
        ];

        $jenisBantuanList = JenisBantuan::where('status', true)->get();
        $petugasList      = User::where('role', 'petugas')->orderBy('nama')->get();

        return view('laporan.index', compact('stats', 'chartData', 'jenisBantuanList', 'petugasList'));
    }

    // ────────────────────────────────────────────────────────────
    // LAPORAN PENGAJUAN
    // ────────────────────────────────────────────────────────────

    public function pengajuan(Request $request)
    {
        $query = $this->buildPengajuanQuery($request);
        $pengajuanList = $query->with(['penerima', 'petugas', 'jenisBantuan'])->get();

        $jenisBantuanList = JenisBantuan::where('status', true)->get();
        $petugasList      = User::where('role', 'petugas')->orderBy('nama')->get();

        return view('laporan.pengajuan', compact('pengajuanList', 'jenisBantuanList', 'petugasList'));
    }

    // ────────────────────────────────────────────────────────────
    // LAPORAN PENYALURAN
    // ────────────────────────────────────────────────────────────

    public function penyaluran(Request $request)
    {
        $query = $this->buildPenyaluranQuery($request);
        $penyaluranList = $query->with(['pengajuan.penerima', 'pengajuan.jenisBantuan', 'petugas'])->get();

        $jenisBantuanList = JenisBantuan::where('status', true)->get();
        $petugasList      = User::where('role', 'petugas')->orderBy('nama')->get();

        return view('laporan.penyaluran', compact('penyaluranList', 'jenisBantuanList', 'petugasList'));
    }

    // ────────────────────────────────────────────────────────────
    // EXPORT PDF
    // ────────────────────────────────────────────────────────────

    public function exportPdf(Request $request, string $jenis)
    {
        $user = auth()->user();

        if ($jenis === 'pengajuan') {
            $data = $this->buildPengajuanQuery($request)
                ->with(['penerima', 'petugas', 'jenisBantuan'])->get();
            $pdf = Pdf::loadView('laporan.pdf.pengajuan', [
                'data'   => $data,
                'filter' => $request->all(),
                'user'   => $user,
            ])->setPaper('a4', 'landscape');
            return $pdf->download('laporan-pengajuan-' . now()->format('Ymd') . '.pdf');
        }

        if ($jenis === 'penyaluran') {
            $data = $this->buildPenyaluranQuery($request)
                ->with(['pengajuan.penerima', 'pengajuan.jenisBantuan', 'petugas'])->get();
            $pdf = Pdf::loadView('laporan.pdf.penyaluran', [
                'data'   => $data,
                'filter' => $request->all(),
                'user'   => $user,
            ])->setPaper('a4', 'landscape');
            return $pdf->download('laporan-penyaluran-' . now()->format('Ymd') . '.pdf');
        }

        abort(404);
    }

    // ────────────────────────────────────────────────────────────
    // EXPORT EXCEL  (Super Admin & Admin only)
    // ────────────────────────────────────────────────────────────

    public function exportExcel(Request $request, string $jenis)
    {
        // Pimpinan tidak boleh export Excel
        abort_if(auth()->user()->role === 'pimpinan', 403);

        $filename = 'laporan-' . $jenis . '-' . now()->format('Ymd-His') . '.xlsx';
        $filepath = storage_path('app/temp/' . $filename);

        // Pastikan direktori temp tersedia
        if (! is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        if ($jenis === 'pengajuan') {
            $rows = $this->buildPengajuanQuery($request)
                ->with(['penerima', 'petugas', 'jenisBantuan'])->get();

            $writer = SimpleExcelWriter::create($filepath);
            $writer->addHeader(['No', 'Kode Pengajuan', 'Nama Penerima', 'NIK', 'Jenis Bantuan', 'Petugas', 'Status', 'Tanggal Pengajuan']);

            foreach ($rows as $i => $row) {
                $writer->addRow([
                    'No'               => $i + 1,
                    'Kode Pengajuan'   => $row->kode_pengajuan,
                    'Nama Penerima'    => $row->penerima->nama ?? '-',
                    'NIK'              => $row->penerima->nik ?? '-',
                    'Jenis Bantuan'    => $row->jenisBantuan->pluck('nama_bantuan')->implode(', '),
                    'Petugas'          => $row->petugas->name ?? '-',
                    'Status'           => StatusHelper::label($row->status)[0],
                    'Tanggal Pengajuan'=> $row->tanggal_pengajuan->format('d/m/Y'),
                ]);
            }

            return response()->download($filepath, $filename)->deleteFileAfterSend(true);
        }

        if ($jenis === 'penyaluran') {
            $rows = $this->buildPenyaluranQuery($request)
                ->with(['pengajuan.penerima', 'pengajuan.jenisBantuan', 'petugas'])->get();

            $writer = SimpleExcelWriter::create($filepath);
            $writer->addHeader(['No', 'Kode Pengajuan', 'Nama Penerima', 'Jenis Bantuan', 'Petugas', 'Tanggal Penyaluran', 'Status']);

            foreach ($rows as $i => $row) {
                $writer->addRow([
                    'No'                => $i + 1,
                    'Kode Pengajuan'    => $row->pengajuan->kode_pengajuan ?? '-',
                    'Nama Penerima'     => $row->pengajuan->penerima->nama ?? '-',
                    'Jenis Bantuan'     => $row->pengajuan->jenisBantuan->pluck('nama_bantuan')->implode(', '),
                    'Petugas'           => $row->petugas->name ?? '-',
                    'Tanggal Penyaluran'=> \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y'),
                    'Status'            => StatusHelper::label($row->status)[0],
                ]);
            }

            return response()->download($filepath, $filename)->deleteFileAfterSend(true);
        }

        abort(404);
    }

    // ────────────────────────────────────────────────────────────
    // PRIVATE – Reusable Query Builders
    // ────────────────────────────────────────────────────────────

    private function buildPengajuanQuery(Request $request)
    {
        $query = Pengajuan::query();

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_pengajuan', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_pengajuan', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('petugas_id')) {
            $query->where('petugas_id', $request->petugas_id);
        }
        if ($request->filled('jenis_bantuan_id')) {
            $query->whereHas('jenisBantuan', fn ($q) => $q->where('jenis_bantuan.id', $request->jenis_bantuan_id));
        }
        if ($request->filled('desa') || $request->filled('kecamatan') || $request->filled('kabupaten')) {
            $query->whereHas('penerima', function ($q) use ($request) {
                if ($request->filled('desa'))       $q->where('desa', 'like', '%' . $request->desa . '%');
                if ($request->filled('kecamatan'))  $q->where('kecamatan', 'like', '%' . $request->kecamatan . '%');
                if ($request->filled('kabupaten'))  $q->where('kabupaten', 'like', '%' . $request->kabupaten . '%');
            });
        }

        return $query->latest('tanggal_pengajuan');
    }

    private function buildPenyaluranQuery(Request $request)
    {
        $query = Penyaluran::query();

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_awal);
        }
        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        if ($request->filled('petugas_id')) {
            $query->where('petugas_id', $request->petugas_id);
        }
        if ($request->filled('jenis_bantuan_id')) {
            $query->whereHas('pengajuan.jenisBantuan', fn ($q) => $q->where('jenis_bantuan.id', $request->jenis_bantuan_id));
        }
        if ($request->filled('desa') || $request->filled('kecamatan') || $request->filled('kabupaten')) {
            $query->whereHas('pengajuan.penerima', function ($q) use ($request) {
                if ($request->filled('desa'))       $q->where('desa', 'like', '%' . $request->desa . '%');
                if ($request->filled('kecamatan'))  $q->where('kecamatan', 'like', '%' . $request->kecamatan . '%');
                if ($request->filled('kabupaten'))  $q->where('kabupaten', 'like', '%' . $request->kabupaten . '%');
            });
        }

        return $query->latest('tanggal');
    }
}
