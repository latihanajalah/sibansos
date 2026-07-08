@props([
    'icon' => 'bi-folder2-open',
    'title' => 'Belum ada data',
    'description' => 'Silakan tambahkan data baru atau kembali lagi nanti.'
])

<div class="sb-empty-state d-flex flex-column align-items-center justify-content-center p-5">
    <div class="bg-light rounded-circle p-3 mb-3 d-inline-flex align-items-center justify-content-center text-muted" style="width: 64px; height: 64px;">
        <i class="bi {{ $icon }} fs-2"></i>
    </div>
    <h5 class="fw-semibold text-dark mb-1">{{ $title }}</h5>
    <p class="text-muted mb-3 mx-auto" style="max-width: 340px; font-size: 0.9rem;">{{ $description }}</p>
    
    @isset($action)
        <div>
            {{ $action }}
        </div>
    @endisset
</div>
