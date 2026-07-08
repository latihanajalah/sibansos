{{-- Shared form partial for Create & Edit user --}}
{{-- Variables: $user (exists on edit) --}}

{{-- Nama --}}
<div class="mb-3">
    <label for="nama" class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
    <input type="text"
           class="form-control @error('nama') is-invalid @enderror"
           id="nama"
           name="nama"
           value="{{ old('nama', $user->nama ?? '') }}"
           placeholder="Masukkan nama lengkap"
           autofocus>
    @error('nama')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Email --}}
<div class="mb-3">
    <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
    <input type="email"
           class="form-control @error('email') is-invalid @enderror"
           id="email"
           name="email"
           value="{{ old('email', $user->email ?? '') }}"
           placeholder="contoh@email.com">
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- No HP --}}
<div class="mb-3">
    <label for="no_hp" class="form-label fw-medium">Nomor HP <span class="text-danger">*</span></label>
    <div class="input-group">
        <span class="input-group-text"><i class="bi bi-phone"></i></span>
        <input type="text"
               class="form-control @error('no_hp') is-invalid @enderror"
               id="no_hp"
               name="no_hp"
               value="{{ old('no_hp', $user->no_hp ?? '') }}"
               placeholder="08xxxxxxxxxx">
        @error('no_hp')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Role --}}
<div class="mb-3">
    <label for="role" class="form-label fw-medium">Role <span class="text-danger">*</span></label>
    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role">
        <option value="" disabled {{ old('role', $user->role ?? '') === '' ? 'selected' : '' }}>-- Pilih Role --</option>
        @foreach(['super_admin' => 'Super Admin', 'admin' => 'Admin', 'petugas' => 'Petugas', 'pimpinan' => 'Pimpinan'] as $val => $label)
            <option value="{{ $val }}" {{ old('role', $user->role ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error('role')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Status --}}
<div class="mb-3">
    <label for="status" class="form-label fw-medium">Status <span class="text-danger">*</span></label>
    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
        <option value="" disabled {{ old('status', $user->status ?? '') === '' ? 'selected' : '' }}>-- Pilih Status --</option>
        <option value="aktif" {{ old('status', $user->status ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="nonaktif" {{ old('status', $user->status ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Password --}}
<div class="mb-3">
    <label for="password" class="form-label fw-medium">
        Password
        @if(isset($user)) <span class="text-muted fw-normal small">(Kosongkan jika tidak ingin mengubah)</span>
        @else <span class="text-danger">*</span>
        @endif
    </label>
    <div class="input-group">
        <input type="password"
               class="form-control @error('password') is-invalid @enderror"
               id="password"
               name="password"
               placeholder="{{ isset($user) ? 'Kosongkan jika tidak berubah' : 'Min. 8 karakter' }}"
               autocomplete="new-password">
        <button class="btn btn-outline-secondary" type="button" id="togglePassword" title="Lihat password">
            <i class="bi bi-eye" id="togglePasswordIcon"></i>
        </button>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Password Confirmation --}}
<div class="mb-4">
    <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Password</label>
    <input type="password"
           class="form-control"
           id="password_confirmation"
           name="password_confirmation"
           placeholder="Ulangi password"
           autocomplete="new-password">
</div>

@push('js')
<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const input = document.getElementById('password');
    const icon = document.getElementById('togglePasswordIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>
@endpush
