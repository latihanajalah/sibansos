@props(['message' => 'Memuat data...', 'size' => 'md'])

<div class="sb-loading-container">
    <div class="spinner-border text-primary" role="status" 
         style="width: {{ $size === 'lg' ? '3rem' : ($size === 'md' ? '2rem' : '1.25rem') }}; height: {{ $size === 'lg' ? '3rem' : ($size === 'md' ? '2rem' : '1.25rem') }};">
        <span class="visually-hidden">Loading...</span>
    </div>
    @if ($message)
        <span class="text-muted mt-3 fw-medium" style="font-size: 0.9rem;">{{ $message }}</span>
    @endif
</div>
