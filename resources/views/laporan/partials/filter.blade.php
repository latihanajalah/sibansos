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
        <div class="d-flex align-items-center justify-content-between">
            <h6 class="mb-0"><i class="bi bi-funnel-fill text-primary me-2"></i>Filter Laporan</h6>
            @if(request()->hasAny(['tanggal_awal','tanggal_akhir','status','petugas_id','jenis_bantuan_id','desa','kecamatan','kabupaten']))
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i>Reset
            </a>
            @endif
        </div>
        <hr class="my-3">
        <div class="row g-3">
            <div class="col-sm-6 col-md-3">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" class="form-control form-control-sm" value="{{ request('tanggal_awal') }}">
            </div>
            <div class="col-sm-6 col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" class="form-control form-control-sm" value="{{ request('tanggal_akhir') }}">
            </div>
            <div class="col-sm-6 col-md-3">
                <label class="form-label">Jenis Bantuan</label>
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
                <label class="form-label">Status</label>
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
                <label class="form-label">Petugas</label>
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
                <label class="form-label">Desa</label>
                <input type="text" name="desa" class="form-control form-control-sm" placeholder="Nama desa..." value="{{ request('desa') }}">
            </div>
            <div class="col-sm-6 col-md-3">
                <label class="form-label">Kecamatan</label>
                <input type="text" name="kecamatan" class="form-control form-control-sm" placeholder="Nama kecamatan..." value="{{ request('kecamatan') }}">
            </div>
            <div class="col-sm-6 col-md-3">
                <label class="form-label">Kabupaten</label>
                <input type="text" name="kabupaten" class="form-control form-control-sm" placeholder="Nama kabupaten..." value="{{ request('kabupaten') }}">
            </div>
            <div class="col-12">
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Terapkan Filter
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
