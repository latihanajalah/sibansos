{{--
    Reusable Filter Component for Laporan
    Props (via $slot or @include with compact):
    - $jenisBantuanList: Collection of JenisBantuan
    - $petugasList: Collection of User (role=petugas)
    - $type: 'pengajuan' | 'penyaluran'
    - $showStatus: bool (default true, penyaluran doesn't have multi-status filter)
--}}
<div class="filter-card mb-4">
    <form method="GET" action="{{ url()->current() }}" id="form-filter">
        <div class="filter-header">
            <i class="bi bi-funnel-fill text-primary"></i>
            <span class="fw-700" style="font-weight:700;">Filter Laporan</span>
            @if(request()->hasAny(['tanggal_awal','tanggal_akhir','status','petugas_id','jenis_bantuan_id','desa','kecamatan','kabupaten']))
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary ms-auto" style="border-radius:7px;font-size:.75rem;">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
            @endif
        </div>
        <div class="filter-body">
            <div class="row g-3">
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-600 small" style="font-weight:600;">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" class="form-control form-control-sm" value="{{ request('tanggal_awal') }}">
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-600 small" style="font-weight:600;">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" class="form-control form-control-sm" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-600 small" style="font-weight:600;">Jenis Bantuan</label>
                    <select name="jenis_bantuan_id" class="form-select form-select-sm">
                        <option value="">Semua Jenis</option>
                        @foreach($jenisBantuanList as $jb)
                        <option value="{{ $jb->id }}" {{ request('jenis_bantuan_id') == $jb->id ? 'selected' : '' }}>
                            {{ $jb->nama_bantuan }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @if($type === 'pengajuan')
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-600 small" style="font-weight:600;">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        @foreach(['menunggu_survei','menunggu_verifikasi','revisi_survei','menunggu_persetujuan','siap_disalurkan','selesai','ditolak_admin','ditolak_pimpinan'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                            {{ \App\Helpers\StatusHelper::label($s)[0] }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-600 small" style="font-weight:600;">Petugas</label>
                    <select name="petugas_id" class="form-select form-select-sm">
                        <option value="">Semua Petugas</option>
                        @foreach($petugasList as $p)
                        <option value="{{ $p->id }}" {{ request('petugas_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-600 small" style="font-weight:600;">Desa</label>
                    <input type="text" name="desa" class="form-control form-control-sm" placeholder="Nama desa..." value="{{ request('desa') }}">
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-600 small" style="font-weight:600;">Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control form-control-sm" placeholder="Nama kecamatan..." value="{{ request('kecamatan') }}">
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="form-label fw-600 small" style="font-weight:600;">Kabupaten</label>
                    <input type="text" name="kabupaten" class="form-control form-control-sm" placeholder="Nama kabupaten..." value="{{ request('kabupaten') }}">
                </div>
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-primary btn-sm px-4" style="border-radius:8px;font-weight:600;">
                        <i class="bi bi-search me-1"></i>Terapkan Filter
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
