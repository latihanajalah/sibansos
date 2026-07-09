@if (session('success'))
    <div id="flash-success" class="alert alert-success alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-check-circle-fill fs-5"></i>
        <div>
            {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ensure flash alerts are visible: scroll to top before showing
            try { if (typeof window.scrollTo === 'function') window.scrollTo({ top: 0, behavior: 'auto' }); } catch (e) {}
            var msg = @json(session('success'));
            var flashEl = document.getElementById('flash-success');

            // If a page action already showed a SweetAlert before reload, skip showing it again
            var skip = false;
            try { skip = sessionStorage.getItem('skipFlashSwal') === '1'; } catch (e) { skip = false; }
            if (skip) {
                try { sessionStorage.removeItem('skipFlashSwal'); } catch (e) {}
                // ensure user sees the bootstrap alert: keep it visible then auto-close after 3s
                if (flashEl) {
                    try { flashEl.scrollIntoView({ block: 'start', behavior: 'auto' }); } catch (e) {}
                    setTimeout(function () {
                        try { var bsAlert = new bootstrap.Alert(flashEl); bsAlert.close(); } catch (e) { flashEl.remove(); }
                    }, 3000);
                }
                return;
            }

            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: msg,
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    position: 'center'
                }).then(function () {
                    if (flashEl) flashEl.remove();
                });
            } else {
                // Fallback: auto-dismiss the bootstrap alert after 3s
                if (flashEl) setTimeout(function () {
                    try { var bsAlert = new bootstrap.Alert(flashEl); bsAlert.close(); } catch (e) { flashEl.remove(); }
                }, 3000);
            }
        });
    </script>
    @endpush
@endif

@if (session('error') || session('danger'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-octagon-fill fs-5"></i>
        <div>
            {{ session('error') ?? session('danger') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div>
            {{ session('warning') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('info'))
    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
        <i class="bi bi-info-circle-fill fs-5"></i>
        <div>
            {{ session('info') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
