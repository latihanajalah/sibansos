{{-- Shared form partial for Create & Edit Jenis Bantuan --}}
{{-- Variables: $jenisBantuan (exists on edit) --}}

{{-- Kode --}}
<div class="mb-3">
    <label for="kode" class="form-label fw-medium">Kode <span class="text-danger">*</span></label>
    <input type="text"
           class="form-control @error('kode') is-invalid @enderror"
           id="kode"
           name="kode"
           value="{{ old('kode', $jenisBantuan->kode ?? '') }}"
           placeholder="Masukkan kode jenis bantuan (misal: PKH, BPNT)"
           autofocus>
    @error('kode')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Nama Bantuan --}}
<div class="mb-3">
    <label for="nama_bantuan" class="form-label fw-medium">Nama Bantuan <span class="text-danger">*</span></label>
    <input type="text"
           class="form-control @error('nama_bantuan') is-invalid @enderror"
           id="nama_bantuan"
           name="nama_bantuan"
           value="{{ old('nama_bantuan', $jenisBantuan->nama_bantuan ?? '') }}"
           placeholder="Masukkan nama jenis bantuan">
    @error('nama_bantuan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Deskripsi --}}
<div class="mb-3">
    <label for="deskripsi" class="form-label fw-medium">Deskripsi</label>
    <textarea class="form-control @error('deskripsi') is-invalid @enderror"
              id="deskripsi"
              name="deskripsi"
              rows="4"
              placeholder="Masukkan deskripsi singkat jenis bantuan">{{ old('deskripsi', $jenisBantuan->deskripsi ?? '') }}</textarea>
    @error('deskripsi')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- Status --}}
<div class="mb-4">
    <label for="status" class="form-label fw-medium">Status <span class="text-danger">*</span></label>
    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
        <option value="" disabled {{ old('status', isset($jenisBantuan) ? ($jenisBantuan->status ? '1' : '0') : '') === '' ? 'selected' : '' }}>-- Pilih Status --</option>
        <option value="1" {{ old('status', isset($jenisBantuan) ? ($jenisBantuan->status ? '1' : '0') : '') === '1' ? 'selected' : '' }}>Aktif</option>
        <option value="0" {{ old('status', isset($jenisBantuan) ? ($jenisBantuan->status ? '1' : '0') : '') === '0' ? 'selected' : '' }}>Nonaktif</option>
    </select>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
