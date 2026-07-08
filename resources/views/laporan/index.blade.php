@extends('layouts.app')

@section('title', 'Statistik & Laporan')

@push('styles')
<style>
    /* ─── Stat Cards ─────────────────────────────── */
    .stat-card {
        border-radius: 16px;
        padding: 1.4rem 1.5rem;
        border: 1.5px solid var(--border-color, #e2e8f0);
        background: #fff;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform .2s, box-shadow .2s;
        height: 100%;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.08); }
    .stat-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .stat-value { font-size: 1.9rem; font-weight: 800; line-height: 1; margin-bottom: .15rem; }
    .stat-label { font-size: .78rem; color: #64748b; font-weight: 500; }

    /* ─── Chart Cards ────────────────────────────── */
    .chart-card {
        background: #fff;
        border: 1.5px solid var(--border-color, #e2e8f0);
        border-radius: 16px;
        overflow: hidden;
    }
    .chart-header {
        padding: 1.1rem 1.5rem .75rem;
        border-bottom: 1.5px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .5rem;
    }
    .chart-header h6 { margin: 0; font-weight: 700; font-size: .9rem; }
    .chart-body { padding: 1.25rem 1.5rem; }
</style>
@endpush

@section('content')

<x-breadcrumb :items="['Laporan & Statistik' => '#']" />

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-800 mb-0" style="font-weight:800;">Statistik & Laporan</h4>
        <p class="text-muted mb-0 small">Ringkasan data dan grafik pengajuan bantuan sosial.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('laporan.pengajuan') }}" class="btn btn-outline-primary btn-sm" style="border-radius:8px;">
            <i class="bi bi-file-earmark-text me-1"></i>Laporan Pengajuan
        </a>
        <a href="{{ route('laporan.penyaluran') }}" class="btn btn-outline-success btn-sm" style="border-radius:8px;">
            <i class="bi bi-truck me-1"></i>Laporan Penyaluran
        </a>
    </div>
</div>

{{-- ─── Stat Cards ─── --}}
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['label'=>'Total Penerima',       'value'=>$stats['total_penerima'],       'icon'=>'bi-people-fill',             'bg'=>'#dbeafe','ic'=>'#2563eb'],
        ['label'=>'Total Pengajuan',       'value'=>$stats['total_pengajuan'],       'icon'=>'bi-file-earmark-text-fill',  'bg'=>'#ede9fe','ic'=>'#7c3aed'],
        ['label'=>'Menunggu Survei',       'value'=>$stats['menunggu_survei'],       'icon'=>'bi-hourglass-split',         'bg'=>'#fef3c7','ic'=>'#d97706'],
        ['label'=>'Menunggu Verifikasi',   'value'=>$stats['menunggu_verifikasi'],   'icon'=>'bi-shield-exclamation',      'bg'=>'#e0f2fe','ic'=>'#0284c7'],
        ['label'=>'Revisi Survei',         'value'=>$stats['revisi_survei'],         'icon'=>'bi-arrow-clockwise',         'bg'=>'#fff7ed','ic'=>'#c2410c'],
        ['label'=>'Menunggu Persetujuan',  'value'=>$stats['menunggu_persetujuan'],  'icon'=>'bi-person-check-fill',       'bg'=>'#f0fdf4','ic'=>'#16a34a'],
        ['label'=>'Siap Disalurkan',       'value'=>$stats['siap_disalurkan'],       'icon'=>'bi-truck',                   'bg'=>'#dcfce7','ic'=>'#15803d'],
        ['label'=>'Selesai',               'value'=>$stats['selesai'],               'icon'=>'bi-check-circle-fill',       'bg'=>'#d1fae5','ic'=>'#059669'],
        ['label'=>'Ditolak',               'value'=>$stats['ditolak'],               'icon'=>'bi-x-circle-fill',           'bg'=>'#fee2e2','ic'=>'#dc2626'],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="col-6 col-md-4 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $card['bg'] }};color:{{ $card['ic'] }};">
                <i class="bi {{ $card['icon'] }}"></i>
            </div>
            <div>
                <div class="stat-value" style="color:{{ $card['ic'] }};">{{ number_format($card['value']) }}</div>
                <div class="stat-label">{{ $card['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ─── Charts Row 1 ─── --}}
<div class="row g-4 mb-4">
    {{-- Pengajuan per Bulan --}}
    <div class="col-12 col-lg-8">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h6><i class="bi bi-bar-chart-fill text-primary me-2"></i>Pengajuan & Penyaluran per Bulan</h6>
                <span class="badge bg-light text-muted" style="font-size:.7rem;">12 Bulan Terakhir</span>
            </div>
            <div class="chart-body">
                <canvas id="chartBulan" height="100"></canvas>
            </div>
        </div>
    </div>

    {{-- Status Donut --}}
    <div class="col-12 col-lg-4">
        <div class="chart-card h-100">
            <div class="chart-header">
                <h6><i class="bi bi-pie-chart-fill text-warning me-2"></i>Status Pengajuan</h6>
            </div>
            <div class="chart-body d-flex align-items-center justify-content-center" style="min-height:250px;">
                <canvas id="chartStatus" style="max-height:240px;"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ─── Charts Row 2 ─── --}}
<div class="row g-4">
    {{-- Jenis Bantuan --}}
    <div class="col-12 col-lg-6">
        <div class="chart-card">
            <div class="chart-header">
                <h6><i class="bi bi-gift-fill text-success me-2"></i>Jenis Bantuan Terpopuler</h6>
            </div>
            <div class="chart-body">
                <canvas id="chartJenis" height="120"></canvas>
            </div>
        </div>
    </div>

    {{-- Ringkasan Tabel --}}
    <div class="col-12 col-lg-6">
        <div class="chart-card">
            <div class="chart-header">
                <h6><i class="bi bi-table text-info me-2"></i>Ringkasan Status</h6>
            </div>
            <div class="chart-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:.875rem;">
                        <thead style="background:#f8fafc;">
                            <tr>
                                <th class="px-4 py-3 fw-600" style="font-weight:600;">Status</th>
                                <th class="px-4 py-3 fw-600 text-end" style="font-weight:600;">Jumlah</th>
                                <th class="px-4 py-3 fw-600 text-end" style="font-weight:600;">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = max($stats['total_pengajuan'], 1); @endphp
                            @foreach([
                                ['label'=>'Menunggu Survei',      'key'=>'menunggu_survei',      'color'=>'warning'],
                                ['label'=>'Menunggu Verifikasi',   'key'=>'menunggu_verifikasi',   'color'=>'info'],
                                ['label'=>'Revisi Survei',         'key'=>'revisi_survei',         'color'=>'warning'],
                                ['label'=>'Menunggu Persetujuan',  'key'=>'menunggu_persetujuan',  'color'=>'primary'],
                                ['label'=>'Siap Disalurkan',       'key'=>'siap_disalurkan',       'color'=>'success'],
                                ['label'=>'Selesai',               'key'=>'selesai',               'color'=>'success'],
                                ['label'=>'Ditolak',               'key'=>'ditolak',               'color'=>'danger'],
                            ] as $row)
                            <tr>
                                <td class="px-4 py-2">
                                    <span class="badge bg-{{ $row['color'] }}-subtle text-{{ $row['color'] }} rounded-pill" style="font-size:.72rem;">{{ $row['label'] }}</span>
                                </td>
                                <td class="px-4 py-2 text-end fw-700" style="font-weight:700;">{{ number_format($stats[$row['key']]) }}</td>
                                <td class="px-4 py-2 text-end text-muted small">{{ $stats['total_pengajuan'] > 0 ? round($stats[$row['key']] / $total * 100, 1) : 0 }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
const COLORS = ['#2563eb','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#f97316','#84cc16','#ec4899'];

// ── Grafik Pengajuan & Penyaluran per Bulan ──
new Chart(document.getElementById('chartBulan'), {
    type: 'bar',
    data: {
        labels: @json($chartData['labels']),
        datasets: [
            {
                label: 'Pengajuan',
                data: @json($chartData['pengajuan']),
                backgroundColor: 'rgba(37,99,235,.75)',
                borderColor: '#2563eb',
                borderWidth: 1.5,
                borderRadius: 6,
            },
            {
                label: 'Penyaluran',
                data: @json($chartData['penyaluran']),
                backgroundColor: 'rgba(16,185,129,.75)',
                borderColor: '#10b981',
                borderWidth: 1.5,
                borderRadius: 6,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

// ── Grafik Status Donut ──
new Chart(document.getElementById('chartStatus'), {
    type: 'doughnut',
    data: {
        labels: @json($chartData['statusPengajuan']['labels']),
        datasets: [{ data: @json($chartData['statusPengajuan']['data']), backgroundColor: COLORS, borderWidth: 2 }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } },
        cutout: '65%'
    }
});

// ── Grafik Jenis Bantuan ──
new Chart(document.getElementById('chartJenis'), {
    type: 'bar',
    data: {
        labels: @json($chartData['jenisBantuan']['labels']),
        datasets: [{
            label: 'Jumlah Pengajuan',
            data: @json($chartData['jenisBantuan']['data']),
            backgroundColor: COLORS.map(c => c + 'cc'),
            borderColor: COLORS,
            borderWidth: 1.5,
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});
</script>
@endpush
