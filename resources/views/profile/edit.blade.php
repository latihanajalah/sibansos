<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark mb-0">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4 d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                        <div>
                            <h1 class="fs-3 fw-semibold text-dark mb-2">Profil Akun</h1>
                            <p class="text-muted mb-0" style="max-width: 40rem;">
                                Perbarui informasi profil Anda, kelola keamanan akun, dan lakukan tindakan penting dengan nyaman.
                            </p>
                        </div>

                        <div class="d-inline-flex align-items-center gap-3 rounded-pill border bg-light px-3 py-2">
                            <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-secondary-subtle text-dark fw-semibold" style="width:48px;height:48px;font-size:1.1rem;">
                                {{ strtoupper(substr(auth()->user()->nama ?? auth()->user()->email, 0, 1)) }}
                            </span>
                            <div class="text-truncate">
                                <p class="mb-0 fw-medium text-dark text-truncate">{{ auth()->user()->nama }}</p>
                                <p class="mb-0 small text-muted text-truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-xl-8">
                <div class="d-flex flex-column gap-4">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card border-danger-subtle bg-danger-subtle shadow-sm rounded-4">
                    <div class="card-body p-4">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>