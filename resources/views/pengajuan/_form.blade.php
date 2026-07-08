{{-- Shared form partial for Create & Edit Pengajuan --}}
{{-- Variables: $pengajuan (exists on edit), $penerimaList, $jenisBantuanList --}}

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: var(--sb-border-radius);
        border-color: #dee2e6;
        min-height: 40px;
        display: flex;
        align-items: center;
    }
</style>
@endpush

{{-- Penerima Bantuan (Select2 Dropdown) --}}
<div class="mb-4">
    <label for="penerima_id" class="form-label fw-medium">Calon Penerima Bantuan <span class="text-danger">*</span></label>
    <select class="form-select select2 @error('penerima_id') is-invalid @enderror" id="penerima_id" name="penerima_id">
        <option value=""></option>
        @foreach($penerimaList as $penerima)
            <option value="{{ $penerima->id }}"
                {{ old('penerima_id', $pengajuan->penerima_id ?? '') == $penerima->id ? 'selected' : '' }}>
                {{ $penerima->nik }} - {{ $penerima->nama }}
            </option>
        @endforeach
    </select>
    @error('penerima_id')
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

{{-- Jenis Bantuan (Checkboxes) --}}
<div class="mb-4">
    <label class="form-label fw-medium d-block">Pilihan Jenis Bantuan <span class="text-danger">*</span></label>
    <div class="row g-3">
        @php
            $selectedBantuan = old('jenis_bantuan_ids', isset($pengajuan) ? $pengajuan->jenisBantuan->pluck('id')->toArray() : []);
        @endphp
        @foreach($jenisBantuanList as $jenisBantuan)
            <div class="col-md-6">
                <div class="card p-3 border-light shadow-sm h-100">
                    <div class="form-check">
                        <input class="form-check-input @error('jenis_bantuan_ids') is-invalid @enderror"
                               type="checkbox"
                               name="jenis_bantuan_ids[]"
                               value="{{ $jenisBantuan->id }}"
                               id="bantuan_{$jenisBantuan->id}"
                               {{ in_array($jenisBantuan->id, $selectedBantuan) ? 'checked' : '' }}>
                        <label class="form-check-label ms-1" for="bantuan_{$jenisBantuan->id}">
                            <strong class="text-dark">{{ $jenisBantuan->kode }}</strong> - {{ $jenisBantuan->nama_bantuan }}
                            @if($jenisBantuan->deskripsi)
                                <small class="text-muted d-block mt-1">{{ $jenisBantuan->deskripsi }}</small>
                            @endif
                        </label>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @error('jenis_bantuan_ids')
        <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
    @enderror
</div>

{{-- Keterangan --}}
<div class="mb-4">
    <label for="keterangan" class="form-label fw-medium">Keterangan Tambahan <span class="text-muted fw-normal">(Opsional)</span></label>
    <textarea class="form-control @error('keterangan') is-invalid @enderror"
              id="keterangan"
              name="keterangan"
              rows="4"
              placeholder="Masukkan alasan pengajuan atau detail tambahan lainnya...">{{ old('keterangan', $pengajuan->keterangan ?? '') }}</textarea>
    @error('keterangan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#penerima_id').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Pilih NIK atau Nama Penerima --',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
