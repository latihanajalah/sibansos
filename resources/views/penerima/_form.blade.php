{{-- Shared form partial for Create & Edit Penerima --}}
{{-- Variables: $penerima (exists on edit) --}}

{{-- DATA IDENTITAS --}}
<h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
    <i class="bi bi-person-bounding-box text-primary me-1"></i> Data Identitas
</h5>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="nik" class="form-label fw-medium">NIK <span class="text-danger">*</span></label>
         <input type="text"
             class="form-control only-number @error('nik') is-invalid @enderror"
               id="nik"
               name="nik"
               value="{{ old('nik', $penerima->nik ?? '') }}"
               placeholder="16 digit nomor NIK"
               maxlength="16"
               autofocus>
        @error('nik')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="no_kk" class="form-label fw-medium">Nomor KK <span class="text-danger">*</span></label>
         <input type="text"
             class="form-control only-number @error('no_kk') is-invalid @enderror"
               id="no_kk"
               name="no_kk"
               value="{{ old('no_kk', $penerima->no_kk ?? '') }}"
               placeholder="16 digit nomor KK"
               maxlength="16">
        @error('no_kk')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="nama" class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
         <input type="text"
             class="form-control only-text @error('nama') is-invalid @enderror"
               id="nama"
               name="nama"
               value="{{ old('nama', $penerima->nama ?? '') }}"
               placeholder="Masukkan nama lengkap">
        @error('nama')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="jenis_kelamin" class="form-label fw-medium">Jenis Kelamin <span class="text-danger">*</span></label>
        <select class="form-select @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin" name="jenis_kelamin">
            <option value="" disabled {{ old('jenis_kelamin', $penerima->jenis_kelamin ?? '') === '' ? 'selected' : '' }}>-- Pilih Jenis Kelamin --</option>
            <option value="Laki-laki" {{ old('jenis_kelamin', $penerima->jenis_kelamin ?? '') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
            <option value="Perempuan" {{ old('jenis_kelamin', $penerima->jenis_kelamin ?? '') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
        </select>
        @error('jenis_kelamin')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="tempat_lahir" class="form-label fw-medium">Tempat Lahir <span class="text-danger">*</span></label>
         <input type="text"
             class="form-control only-text @error('tempat_lahir') is-invalid @enderror"
               id="tempat_lahir"
               name="tempat_lahir"
               value="{{ old('tempat_lahir', $penerima->tempat_lahir ?? '') }}"
               placeholder="Kota tempat lahir">
        @error('tempat_lahir')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="tanggal_lahir" class="form-label fw-medium">Tanggal Lahir <span class="text-danger">*</span></label>
        <input type="date"
               class="form-control @error('tanggal_lahir') is-invalid @enderror"
               id="tanggal_lahir"
               name="tanggal_lahir"
               value="{{ old('tanggal_lahir', isset($penerima->tanggal_lahir) ? $penerima->tanggal_lahir->format('Y-m-d') : '') }}">
        @error('tanggal_lahir')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- ALAMAT --}}
<h5 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
    <i class="bi bi-geo-alt text-primary me-1"></i> Data Alamat
</h5>
<div class="row g-3 mb-4">
    <div class="col-12">
        <label for="alamat" class="form-label fw-medium">Alamat Jalan / Dusun / RT-RW <span class="text-danger">*</span></label>
        <textarea class="form-control @error('alamat') is-invalid @enderror"
                  id="alamat"
                  name="alamat"
                  rows="3"
                  placeholder="Nama jalan, nomor rumah, RT/RW, dsb.">{{ old('alamat', $penerima->alamat ?? '') }}</textarea>
        @error('alamat')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="rt" class="form-label fw-medium">RT <span class="text-danger">*</span></label>
         <input type="text"
             class="form-control only-number @error('rt') is-invalid @enderror"
               id="rt"
               name="rt"
               value="{{ old('rt', $penerima->rt ?? '') }}"
               placeholder="Contoh: 003">
        @error('rt')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="rw" class="form-label fw-medium">RW <span class="text-danger">*</span></label>
         <input type="text"
             class="form-control only-number @error('rw') is-invalid @enderror"
               id="rw"
               name="rw"
               value="{{ old('rw', $penerima->rw ?? '') }}"
               placeholder="Contoh: 002">
        @error('rw')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="desa" class="form-label fw-medium">Desa / Kelurahan <span class="text-danger">*</span></label>
         <input type="text"
             class="form-control only-text @error('desa') is-invalid @enderror"
               id="desa"
               name="desa"
               value="{{ old('desa', $penerima->desa ?? '') }}"
               placeholder="Nama desa / kelurahan">
        @error('desa')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="kecamatan" class="form-label fw-medium">Kecamatan <span class="text-danger">*</span></label>
         <input type="text"
             class="form-control only-text @error('kecamatan') is-invalid @enderror"
               id="kecamatan"
               name="kecamatan"
               value="{{ old('kecamatan', $penerima->kecamatan ?? '') }}"
               placeholder="Nama kecamatan">
        @error('kecamatan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="kabupaten" class="form-label fw-medium">Kabupaten / Kota <span class="text-danger">*</span></label>
         <input type="text"
             class="form-control only-text @error('kabupaten') is-invalid @enderror"
               id="kabupaten"
               name="kabupaten"
               value="{{ old('kabupaten', $penerima->kabupaten ?? '') }}"
               placeholder="Nama kabupaten / kota">
        @error('kabupaten')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="provinsi" class="form-label fw-medium">Provinsi <span class="text-danger">*</span></label>
         <input type="text"
             class="form-control only-text @error('provinsi') is-invalid @enderror"
               id="provinsi"
               name="provinsi"
               value="{{ old('provinsi', $penerima->provinsi ?? '') }}"
               placeholder="Nama provinsi">
        @error('provinsi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- KONTAK --}}
<h5 class="fw-bold text-dark border-bottom pb-2 mb-3 mt-4">
    <i class="bi bi-telephone text-primary me-1"></i> Kontak
</h5>
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <label for="no_hp" class="form-label fw-medium">Nomor HP</label>
         <input type="text"
             class="form-control only-number @error('no_hp') is-invalid @enderror"
               id="no_hp"
               name="no_hp"
               value="{{ old('no_hp', $penerima->no_hp ?? '') }}"
               placeholder="Contoh: 08123456789">
        @error('no_hp')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
