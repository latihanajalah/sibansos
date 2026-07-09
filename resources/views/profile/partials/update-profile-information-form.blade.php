<section>
    <header class="mb-4">
        <h2 class="fs-5 fw-medium text-dark mb-1">
            {{ __('Profile Information') }}
        </h2>

        <p class="text-muted small mb-0">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-3">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="nama" class="form-label">{{ __('Nama') }}</label>
            <input id="nama" name="nama" type="text"
                   class="form-control @error('nama') is-invalid @enderror"
                   value="{{ old('nama', $user->nama) }}"
                   required autofocus autocomplete="name">
            @error('nama')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $user->email) }}"
                   required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="small text-dark mb-1">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" type="submit" class="btn btn-link btn-sm p-0 align-baseline">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="small fw-medium text-success mb-0">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <span class="small text-muted" id="profile-saved-msg">{{ __('Saved.') }}</span>
                <script>
                    setTimeout(() => {
                        const el = document.getElementById('profile-saved-msg');
                        if (el) el.style.display = 'none';
                    }, 2000);
                </script>
            @endif
        </div>
    </form>
</section>