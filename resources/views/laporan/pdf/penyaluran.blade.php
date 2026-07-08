<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 15mm 12mm; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 9pt; color: #1e293b; }

    /* Header */
    .pdf-header { border-bottom: 3px solid #10b981; padding-bottom: 10px; margin-bottom: 14px; display: flex; justify-content: space-between; align-items: flex-start; }
    .logo-box { width: 42px; height: 42px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 20pt; color: white; text-align: center; line-height: 42px; }
    .header-title { font-size: 14pt; font-weight: bold; color: #10b981; margin-bottom: 2px; }
    .header-sub { font-size: 8pt; color: #64748b; }

    /* Filter summary */
    .filter-summary { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; margin-bottom: 12px; font-size: 8pt; color: #475569; }
    .filter-summary .fi { display: inline-block; margin-right: 16px; }

    /* Table */
    table { width: 100%; border-collapse: collapse; font-size: 8pt; }
    thead tr { background: #10b981; color: #fff; }
    thead th { padding: 7px 8px; font-weight: bold; text-align: left; border: 1px solid #059669; font-size: 7.5pt; text-transform: uppercase; letter-spacing: .03em; }
    tbody tr:nth-child(even) { background: #f8fafc; }
    tbody tr:nth-child(odd) { background: #ffffff; }
    tbody td { padding: 6px 8px; border: 1px solid #e2e8f0; vertical-align: middle; }

    /* Status badges */
    .badge { display: inline-block; padding: 2px 7px; border-radius: 50px; font-size: 7pt; font-weight: bold; }
    .b-success  { background: #d1fae5; color: #065f46; }

    /* Footer */
    .pdf-footer { position: fixed; bottom: 0; left: 0; right: 0; border-top: 1px solid #e2e8f0; padding: 5px 12mm; font-size: 7.5pt; color: #94a3b8; display: flex; justify-content: space-between; }
</style>
</head>
<body>

<div class="pdf-header">
    <div style="display:flex;align-items:center;gap:10px;">
        <div class="logo-box">&#10003;</div>
        <div>
            <div class="header-title">Laporan Penyaluran Bantuan Sosial</div>
            <div class="header-sub">Sistem Informasi Bantuan Sosial (SiBansos)</div>
        </div>
    </div>
    <div style="text-align:right;font-size:8pt;color:#64748b;">
        <div>Dicetak: {{ now()->format('d M Y, H:i') }}</div>
        <div>Oleh: {{ $user->name }}</div>
        <div style="margin-top:3px;font-weight:bold;color:#10b981;">Total: {{ $data->count() }} data</div>
    </div>
</div>

{{-- Filter Summary --}}
@if(array_filter($filter))
<div class="filter-summary">
    <strong>Filter diterapkan:</strong>
    @if(!empty($filter['tanggal_awal'])) <span class="fi">📅 Dari: {{ $filter['tanggal_awal'] }}</span> @endif
    @if(!empty($filter['tanggal_akhir'])) <span class="fi">s/d: {{ $filter['tanggal_akhir'] }}</span> @endif
    @if(!empty($filter['desa'])) <span class="fi">Desa: {{ $filter['desa'] }}</span> @endif
    @if(!empty($filter['kecamatan'])) <span class="fi">Kecamatan: {{ $filter['kecamatan'] }}</span> @endif
    @if(!empty($filter['kabupaten'])) <span class="fi">Kabupaten: {{ $filter['kabupaten'] }}</span> @endif
</div>
@endif

<table>
    <thead>
        <tr>
            <th style="width:30px;">No</th>
            <th style="width:150px;">Kode Pengajuan</th>
            <th>Nama Penerima</th>
            <th>Jenis Bantuan</th>
            <th>Petugas</th>
            <th style="width:120px;">Tgl Penyaluran</th>
            <th style="width:100px;">Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse($data as $i => $row)
        <tr>
            <td style="text-align:center;color:#94a3b8;">{{ $i + 1 }}</td>
            <td style="font-family:monospace;font-size:7.5pt;">{{ $row->pengajuan->kode_pengajuan ?? '-' }}</td>
            <td><strong>{{ $row->pengajuan->penerima->nama ?? '-' }}</strong></td>
            <td>{{ $row->pengajuan->jenisBantuan->pluck('nama_bantuan')->implode(', ') ?? '-' }}</td>
            <td>{{ $row->petugas->name ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}</td>
            <td>
                <span class="badge b-success">Selesai</span>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;color:#94a3b8;padding:16px;">Tidak ada data.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="pdf-footer">
    <span>SiBansos &copy; {{ date('Y') }} – Dokumen ini dicetak secara otomatis oleh sistem.</span>
    <span>Halaman <script type="text/php">if (isset($pdf)) { $pdf->page_script('$pdf->text(270, 820, "Hal. $PAGE_NUM dari $PAGE_COUNT", null, 8);'); }</script></span>
</div>

</body>
</html>
