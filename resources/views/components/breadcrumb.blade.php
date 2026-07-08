@props(['items' => []])

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-transparent p-0 m-0" style="font-size: 0.9rem;">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}" class="text-decoration-none d-inline-flex align-items-center gap-1">
                <i class="bi bi-house-door-fill text-muted"></i>
                <span>Home</span>
            </a>
        </li>
        @foreach($items as $label => $link)
            @if(!$loop->last)
                <li class="breadcrumb-item">
                    <a href="{{ $link }}" class="text-decoration-none">{{ $label }}</a>
                </li>
            @else
                <li class="breadcrumb-item active text-dark fw-medium" aria-current="page">{{ $label }}</li>
            @endif
        @endforeach
    </ol>
</nav>
