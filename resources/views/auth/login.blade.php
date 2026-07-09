<x-guest-layout>
    <div>
        <h1>Masuk</h1>
        <p>Masukkan kredensial Anda untuk melanjutkan ke portal petugas.</p>

        @if ($errors->any())
            <div class="error-message show">
                <strong>Ups! Ada kesalahan.</strong>
                <ul style="margin-top:.5rem;margin-bottom:0;padding-left:1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div style="background:#dcfce7;border-left:4px solid #16a34a;color:#166534;padding:1rem;border-radius:.85rem;margin-bottom:1.5rem;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    @class(['is-invalid' => $errors->has('email')])
                >
                @error('email')
                    <p style="color:#dc2626;font-size:.875rem;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    @class(['is-invalid' => $errors->has('password')])
                >
                @error('password')
                    <p style="color:#dc2626;font-size:.875rem;margin-top:.35rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="remember_me" name="remember">
                <label for="remember_me">Ingat saya</label>
            </div>

            <button type="submit" class="btn-login">
                Masuk
            </button>

            @if (Route::has('password.request'))
                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">Lupa Password?</a>
                </div>
            @endif
        </form>
    </div>
</x-guest-layout>
