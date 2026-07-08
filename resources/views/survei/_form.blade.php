{{-- Shared form partial for Create & Edit Survei --}}
{{-- Variables: $pengajuan (always), $survei (exists on edit) --}}

{{-- ══════════════════════════════════════════════
     BAGIAN 1 — KONDISI RUMAH
═══════════════════════════════════════════════ --}}
<div class="card card-saas border-0 p-4 mb-4">
    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
        <span class="icon-circle bg-primary-light text-primary">
            <i class="bi bi-house-door"></i>
        </span>
        Kondisi Rumah
    </h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="status_rumah" class="form-label fw-medium">Status Rumah <span class="text-danger">*</span></label>
            <select class="form-select @error('status_rumah') is-invalid @enderror" id="status_rumah" name="status_rumah">
                <option value="">-- Pilih Status --</option>
                @foreach(['Layak Huni', 'Tidak Layak Huni', 'Rusak Ringan', 'Rusak Sedang', 'Rusak Berat'] as $opt)
                    <option value="{{ $opt }}" {{ old('status_rumah', $survei->status_rumah ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
            @error('status_rumah') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label for="kepemilikan_rumah" class="form-label fw-medium">Kepemilikan Rumah <span class="text-danger">*</span></label>
            <select class="form-select @error('kepemilikan_rumah') is-invalid @enderror" id="kepemilikan_rumah" name="kepemilikan_rumah">
                <option value="">-- Pilih Kepemilikan --</option>
                @foreach(['Milik Sendiri', 'Kontrak/Sewa', 'Menumpang', 'Rumah Dinas', 'Lainnya'] as $opt)
                    <option value="{{ $opt }}" {{ old('kepemilikan_rumah', $survei->kepemilikan_rumah ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
            @error('kepemilikan_rumah') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     BAGIAN 2 — BANGUNAN
═══════════════════════════════════════════════ --}}
<div class="card card-saas border-0 p-4 mb-4">
    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
        <span class="icon-circle bg-warning-light text-warning">
            <i class="bi bi-building"></i>
        </span>
        Kondisi Bangunan
    </h6>
    <div class="row g-3">
        <div class="col-md-4">
            <label for="jenis_lantai" class="form-label fw-medium">Jenis Lantai <span class="text-danger">*</span></label>
            <select class="form-select @error('jenis_lantai') is-invalid @enderror" id="jenis_lantai" name="jenis_lantai">
                <option value="">-- Pilih --</option>
                @foreach(['Keramik', 'Semen/Beton', 'Tanah', 'Kayu', 'Lainnya'] as $opt)
                    <option value="{{ $opt }}" {{ old('jenis_lantai', $survei->jenis_lantai ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
            @error('jenis_lantai') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
            <label for="jenis_dinding" class="form-label fw-medium">Jenis Dinding <span class="text-danger">*</span></label>
            <select class="form-select @error('jenis_dinding') is-invalid @enderror" id="jenis_dinding" name="jenis_dinding">
                <option value="">-- Pilih --</option>
                @foreach(['Tembok Bata', 'Kayu/Papan', 'Bambu', 'Tripleks', 'Lainnya'] as $opt)
                    <option value="{{ $opt }}" {{ old('jenis_dinding', $survei->jenis_dinding ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
            @error('jenis_dinding') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-4">
            <label for="jenis_atap" class="form-label fw-medium">Jenis Atap <span class="text-danger">*</span></label>
            <select class="form-select @error('jenis_atap') is-invalid @enderror" id="jenis_atap" name="jenis_atap">
                <option value="">-- Pilih --</option>
                @foreach(['Genteng', 'Seng/Metal', 'Asbes', 'Daun/Rumbia', 'Lainnya'] as $opt)
                    <option value="{{ $opt }}" {{ old('jenis_atap', $survei->jenis_atap ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
            @error('jenis_atap') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     BAGIAN 3 — PENGHUNI
═══════════════════════════════════════════════ --}}
<div class="card card-saas border-0 p-4 mb-4">
    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
        <span class="icon-circle bg-success-light text-success">
            <i class="bi bi-people"></i>
        </span>
        Data Penghuni
    </h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="jumlah_kamar" class="form-label fw-medium">Jumlah Kamar <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('jumlah_kamar') is-invalid @enderror"
                   id="jumlah_kamar" name="jumlah_kamar" min="0"
                   value="{{ old('jumlah_kamar', $survei->jumlah_kamar ?? '') }}"
                   placeholder="0">
            @error('jumlah_kamar') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label for="jumlah_penghuni" class="form-label fw-medium">Jumlah Penghuni <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('jumlah_penghuni') is-invalid @enderror"
                   id="jumlah_penghuni" name="jumlah_penghuni" min="1"
                   value="{{ old('jumlah_penghuni', $survei->jumlah_penghuni ?? '') }}"
                   placeholder="1">
            @error('jumlah_penghuni') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     BAGIAN 4 — EKONOMI
═══════════════════════════════════════════════ --}}
<div class="card card-saas border-0 p-4 mb-4">
    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
        <span class="icon-circle bg-info-light text-info">
            <i class="bi bi-wallet2"></i>
        </span>
        Kondisi Ekonomi
    </h6>
    <div class="row g-3">
        <div class="col-md-6">
            <label for="pekerjaan" class="form-label fw-medium">Pekerjaan <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('pekerjaan') is-invalid @enderror"
                   id="pekerjaan" name="pekerjaan"
                   value="{{ old('pekerjaan', $survei->pekerjaan ?? '') }}"
                   placeholder="Contoh: Petani, Buruh Harian, Tidak Bekerja">
            @error('pekerjaan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label for="penghasilan" class="form-label fw-medium">Penghasilan / Bulan <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-white">Rp</span>
                <input type="number" class="form-control @error('penghasilan') is-invalid @enderror"
                       id="penghasilan" name="penghasilan" min="0"
                       value="{{ old('penghasilan', $survei->penghasilan ?? '') }}"
                       placeholder="0">
            </div>
            @error('penghasilan') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-3">
            <label for="jumlah_tanggungan" class="form-label fw-medium">Jumlah Tanggungan <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('jumlah_tanggungan') is-invalid @enderror"
                   id="jumlah_tanggungan" name="jumlah_tanggungan" min="0"
                   value="{{ old('jumlah_tanggungan', $survei->jumlah_tanggungan ?? '') }}"
                   placeholder="0">
            @error('jumlah_tanggungan') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     BAGIAN 5 — KEPEMILIKAN ASET
═══════════════════════════════════════════════ --}}
<div class="card card-saas border-0 p-4 mb-4">
    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
        <span class="icon-circle bg-danger-light text-danger">
            <i class="bi bi-boxes"></i>
        </span>
        Kepemilikan Aset
    </h6>
    <div class="row g-3">
        @php
            $asets = [
                'punya_motor'  => ['icon' => 'bi-scooter',         'label' => 'Memiliki Motor'],
                'punya_mobil'  => ['icon' => 'bi-car-front',       'label' => 'Memiliki Mobil'],
                'punya_sawah'  => ['icon' => 'bi-flower1',         'label' => 'Memiliki Sawah / Ladang'],
                'punya_ternak' => ['icon' => 'bi-egg-fried',       'label' => 'Memiliki Ternak'],
            ];
        @endphp
        @foreach($asets as $field => $aset)
            <div class="col-md-6">
                <div class="card border-light p-3 h-100 {{ old($field, $survei->$field ?? false) ? 'border-primary bg-primary-light' : '' }}"
                     id="card_{{ $field }}" style="cursor: pointer;"
                     onclick="document.getElementById('{{ $field }}').click()">
                    <div class="form-check mb-0">
                        <input class="form-check-input aset-check" type="checkbox"
                               name="{{ $field }}" value="1"
                               id="{{ $field }}"
                               data-card="card_{{ $field }}"
                               {{ old($field, $survei->$field ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label d-flex align-items-center gap-2 fw-medium" for="{{ $field }}"
                               style="pointer-events: none;">
                            <i class="bi {{ $aset['icon'] }} fs-5 text-primary"></i>
                            {{ $aset['label'] }}
                        </label>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- ══════════════════════════════════════════════
     BAGIAN 6 — CATATAN
═══════════════════════════════════════════════ --}}
<div class="card card-saas border-0 p-4 mb-4">
    <h6 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
        <span class="icon-circle bg-secondary-light text-secondary">
            <i class="bi bi-journal-text"></i>
        </span>
        Catatan Survei <span class="fw-normal text-muted">(Opsional)</span>
    </h6>
    <textarea class="form-control @error('catatan') is-invalid @enderror"
              id="catatan" name="catatan" rows="4"
              placeholder="Tuliskan catatan atau temuan penting selama survei lapangan...">{{ old('catatan', $survei->catatan ?? '') }}</textarea>
    @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- ══════════════════════════════════════════════
     BAGIAN 7 — UPLOAD FOTO
═══════════════════════════════════════════════ --}}
<div class="card card-saas border-0 p-4 mb-4">
    <h6 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
        <span class="icon-circle bg-primary-light text-primary">
            <i class="bi bi-camera"></i>
        </span>
        Foto Dokumentasi
    </h6>
    <p class="text-muted small mb-4">Upload foto kondisi rumah penerima. Format: JPG/JPEG/PNG, maks. 2 MB per foto.</p>

    @php
        $fotoSlots = [
            'foto_tampak_depan' => ['label' => 'Tampak Depan Rumah', 'required' => true,  'icon' => 'bi-house',          'kategori' => 'Tampak Depan Rumah'],
            'foto_ruang_tamu'   => ['label' => 'Ruang Tamu',         'required' => true,  'icon' => 'bi-tv',             'kategori' => 'Ruang Tamu'],
            'foto_dapur'        => ['label' => 'Dapur',              'required' => true,  'icon' => 'bi-cup-hot',        'kategori' => 'Dapur'],
            'foto_kamar'        => ['label' => 'Kamar',              'required' => false, 'icon' => 'bi-door-open',      'kategori' => 'Kamar'],
            'foto_kamar_mandi'  => ['label' => 'Kamar Mandi',        'required' => false, 'icon' => 'bi-droplet',        'kategori' => 'Kamar Mandi'],
        ];
        // Existing photos on edit mode
        $existingFoto = isset($survei) ? $survei->foto->keyBy('kategori') : collect();
    @endphp

    <div class="row g-3">
        @foreach($fotoSlots as $field => $slot)
            <div class="col-md-4">
                <div class="card border @error($field) border-danger @else border-light @enderror h-100"
                     style="min-height: 180px;">
                    {{-- Preview existing photo --}}
                    @if(isset($existingFoto[$slot['kategori']]))
                        <img src="{{ asset('storage/' . $existingFoto[$slot['kategori']]->file) }}"
                             class="card-img-top object-fit-cover"
                             style="height: 140px; object-fit: cover; border-radius: 8px 8px 0 0;"
                             alt="{{ $slot['label'] }}">
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center text-muted"
                             style="height: 140px; background: #f8f9fa; border-radius: 8px 8px 0 0;">
                            <i class="bi {{ $slot['icon'] }} fs-2 mb-1"></i>
                            <small style="font-size: 0.72rem;">Belum ada foto</small>
                        </div>
                    @endif
                    <div class="card-body p-3">
                        <p class="mb-1 fw-medium text-dark" style="font-size: 0.85rem;">
                            {{ $slot['label'] }}
                            @if($slot['required'])
                                <span class="text-danger">*</span>
                            @else
                                <span class="text-muted fw-normal">(Opsional)</span>
                            @endif
                        </p>
                        <input type="file"
                               class="form-control form-control-sm @error($field) is-invalid @enderror"
                               id="{{ $field }}" name="{{ $field }}"
                               accept="image/jpg,image/jpeg,image/png">
                        @error($field) <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if(isset($existingFoto) && $existingFoto->isNotEmpty())
        <p class="text-muted small mt-3 mb-0">
            <i class="bi bi-info-circle me-1"></i>
            Upload foto baru hanya untuk mengganti foto yang sudah ada. Biarkan kosong jika tidak ingin mengubah.
        </p>
    @endif
</div>

{{-- ══════════════════════════════════════════════
     BAGIAN 8 — UPLOAD DOKUMEN
═══════════════════════════════════════════════ --}}
<div class="card card-saas border-0 p-4 mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <span class="icon-circle bg-warning-light text-warning">
                <i class="bi bi-file-earmark-text"></i>
            </span>
            Dokumen Pendukung <span class="fw-normal text-muted">(Opsional)</span>
        </h6>
        <button type="button" class="btn btn-sm btn-outline-primary" id="btn-tambah-dokumen">
            <i class="bi bi-plus-lg me-1"></i> Tambah Dokumen
        </button>
    </div>

    {{-- Existing Documents (Edit Mode) --}}
    @if(isset($pengajuan) && $pengajuan->dokumen->isNotEmpty())
        <div class="mb-3">
            <p class="text-muted small mb-2">Dokumen yang sudah diupload:</p>
            <div class="d-flex flex-wrap gap-2">
                @foreach($pengajuan->dokumen as $dok)
                    <div class="card border-light p-2 d-flex flex-row align-items-center gap-2" style="font-size: 0.85rem;">
                        <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>
                        <span>{{ $dok->nama_dokumen }}</span>
                        <a href="{{ asset('storage/' . $dok->file) }}" target="_blank"
                           class="btn btn-sm btn-outline-secondary py-0 px-2" title="Lihat">
                            <i class="bi bi-eye"></i>
                        </a>
                        @if(auth()->user()->role === 'petugas' && $pengajuan->status === 'menunggu_verifikasi')
                            <form action="{{ route('dokumen.destroy', $dok) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger py-0 px-2" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <p class="text-muted small mb-3">Contoh: KTP, KK, Surat Keterangan Tidak Mampu. Format: PDF/JPG/PNG, maks. 5 MB.</p>

    <div id="dokumen-container">
        {{-- Dynamic dokumen rows --}}
    </div>

    <p class="text-muted small mt-2" id="empty-dokumen-hint">
        <i class="bi bi-info-circle me-1"></i>
        Klik tombol "Tambah Dokumen" untuk menambahkan dokumen pendukung.
    </p>
</div>

@push('js')
<script>
    // ── Aset Checkbox Card Toggle ──────────────────
    document.querySelectorAll('.aset-check').forEach(function(chk) {
        chk.addEventListener('change', function() {
            const card = document.getElementById(this.getAttribute('data-card'));
            if (this.checked) {
                card.classList.add('border-primary', 'bg-primary-light');
            } else {
                card.classList.remove('border-primary', 'bg-primary-light');
            }
        });
    });

    // ── Dynamic Dokumen Rows ────────────────────────
    let dokIdx = 0;

    function addDokumenRow() {
        const hint = document.getElementById('empty-dokumen-hint');
        hint.style.display = 'none';

        const container = document.getElementById('dokumen-container');
        const row = document.createElement('div');
        row.classList.add('row', 'g-2', 'mb-3', 'align-items-end', 'dokumen-row');
        row.dataset.idx = dokIdx;

        row.innerHTML = `
            <div class="col-md-5">
                <label class="form-label small fw-medium text-muted">Nama Dokumen</label>
                <input type="text" class="form-control" name="dokumen[${dokIdx}][nama]"
                       placeholder="Contoh: KTP, KK, SKTM..." required>
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-medium text-muted">File Dokumen</label>
                <input type="file" class="form-control" name="dokumen[${dokIdx}][file]"
                       accept=".pdf,.jpg,.jpeg,.png" required>
            </div>
            <div class="col-md-1 d-flex justify-content-end">
                <button type="button" class="btn btn-sm btn-outline-danger py-2"
                        onclick="removeRow(this)" title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(row);
        dokIdx++;
    }

    function removeRow(btn) {
        btn.closest('.dokumen-row').remove();
        if (document.querySelectorAll('.dokumen-row').length === 0) {
            document.getElementById('empty-dokumen-hint').style.display = 'block';
        }
    }

    document.getElementById('btn-tambah-dokumen').addEventListener('click', addDokumenRow);
</script>
@endpush

@push('css')
<style>
    .icon-circle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        font-size: 1rem;
    }
    .bg-primary-light  { background-color: rgba(var(--bs-primary-rgb), 0.1); }
    .bg-warning-light  { background-color: rgba(var(--bs-warning-rgb), 0.12); }
    .bg-success-light  { background-color: rgba(var(--bs-success-rgb), 0.1); }
    .bg-info-light     { background-color: rgba(var(--bs-info-rgb), 0.1); }
    .bg-danger-light   { background-color: rgba(var(--bs-danger-rgb), 0.1); }
    .bg-secondary-light{ background-color: rgba(var(--bs-secondary-rgb), 0.1); }
</style>
@endpush
