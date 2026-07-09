@extends('layouts.app')

@section('title', 'Master Jenis Bantuan')

@push('css')
<style>
    /* ─── Table Card ─────────────────────────────── */
    .jenis-bantuan-table-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }
    .jenis-bantuan-table thead tr th {
        background: #f8fafc;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        font-weight: 700;
        color: #64748b;
        padding: .9rem 1.25rem;
        border-bottom: 1.5px solid #eef1f5;
        white-space: nowrap;
    }
    .jenis-bantuan-table tbody tr td {
        padding: .85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .jenis-bantuan-table tbody tr:last-child td { border-bottom: none; }
    .jenis-bantuan-table tbody tr { transition: background .15s; }
    .jenis-bantuan-table tbody tr:hover { background: #f8fafc; }

    .status-badge {
        font-size: .72rem;
        font-weight: 600;
        padding: .32rem .7rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        white-space: nowrap;
    }

    .btn-icon-action {
        width: 34px; height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        padding: 0;
    }

    /* ─── Filter / Search bar (aligned in one row) ─── */
    .filter-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.1rem 1.25rem;
    }
    .search-inline-form .input-group {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: none;
    }
    .search-inline-form .input-group-text {
        border-right: 0;
    }
    .search-inline-form .form-control {
        border-left: 0;
        box-shadow: none;
    }
    .search-inline-form .form-control:focus {
        box-shadow: none;
        border-color: #ced4da;
    }
    .search-inline-form .btn {
        white-space: nowrap;
    }
    @media (max-width: 575.98px) {
        .search-inline-form .btn span.btn-label { display: none; }
    }

    /* ─── Modal styling ─── */
    .modal-content-saas {
        border: none;
        border-radius: 18px;
        overflow: hidden;
    }
    .modal-header-saas {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: #fff;
        border: none;
        padding: 1.25rem 1.5rem;
    }
    .modal-header-saas .modal-title {
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .modal-header-saas .btn-close {
        filter: brightness(0) invert(1);
        opacity: .85;
    }
    .modal-body-saas {
        padding: 1.5rem;
    }
    .modal-footer-saas {
        border-top: 1px solid #eef1f5;
        padding: 1rem 1.5rem;
    }

    /* Detail modal */
    .detail-band {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 14px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.25rem;
    }
    .detail-band .icon-circle {
        width: 60px; height: 60px;
        background: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .detail-field-label {
        font-size: .78rem;
        color: #64748b;
        margin-bottom: .2rem;
    }
    .detail-field-value {
        font-weight: 600;
        color: #212529;
    }
    .detail-desc-box {
        background: #f8fafc;
        border: 1px solid #eef1f5;
        border-radius: 10px;
        padding: .85rem 1rem;
        min-height: 70px;
        font-size: .92rem;
        color: #212529;
        white-space: pre-line;
    }

    /* Row highlight while saving */
    .row-loading { opacity: .5; pointer-events: none; }
</style>
@endpush

@section('content')

<x-breadcrumb :items="['Jenis Bantuan' => '#']" />

{{-- Page Header --}}
<div class="page-header mb-4">
    <div class="page-header-content">
        <h2>Master Jenis Bantuan</h2>
        <p>Kelola daftar bantuan sosial yang terintegrasi di sistem.</p>
    </div>
    <div class="page-header-actions">
        <button type="button" class="btn btn-primary d-flex align-items-center gap-2" onclick="openCreateModal()">
            <i class="bi bi-plus-lg"></i> Tambah Jenis Bantuan
        </button>
    </div>
</div>

{{-- ─── Stat Cards ─── --}}
@php
    $statusCounts = [
        'aktif'    => $jenisBantuanList->getCollection()->where('status', true)->count(),
        'nonaktif' => $jenisBantuanList->getCollection()->where('status', false)->count(),
    ];

    $jbStatCards = [
        ['label' => 'Total Jenis Bantuan', 'value' => $jenisBantuanList->total(), 'icon' => 'bi-gift-fill',         'bg' => '#dbeafe', 'ic' => '#2563eb'],
        ['label' => 'Aktif',               'value' => $statusCounts['aktif'],     'icon' => 'bi-check-circle-fill', 'bg' => '#dcfce7', 'ic' => '#16a34a'],
        ['label' => 'Nonaktif',            'value' => $statusCounts['nonaktif'],  'icon' => 'bi-x-circle-fill',     'bg' => '#fee2e2', 'ic' => '#dc2626'],
    ];
@endphp

<div class="row g-4 mb-5">
    @foreach($jbStatCards as $card)
    <div class="col-6 col-md-4">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background:{{ $card['bg'] }}; color:{{ $card['ic'] }};">
                    <i class="bi {{ $card['icon'] }} fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">{{ $card['label'] }}</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($card['value']) }}</h3>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter Card — search input & button aligned on one row --}}
<div class="filter-card mb-4">
    <form method="GET" action="{{ route('jenis-bantuan.index') }}" class="search-inline-form">
        <label class="form-label fw-medium mb-2">Cari Jenis Bantuan</label>
        <div class="d-flex flex-wrap gap-2">
            <div class="input-group flex-grow-1" style="min-width: 220px;">
                <span class="input-group-text bg-white">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Cari kode atau nama bantuan..."
                       value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-search"></i> <span class="btn-label">Cari</span>
            </button>
            @if(request('search'))
                <a href="{{ route('jenis-bantuan.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                    <i class="bi bi-x-lg"></i> <span class="btn-label">Reset</span>
                </a>
            @endif
        </div>
    </form>
</div>

{{-- Table Card --}}
<div class="jenis-bantuan-table-card">
    <div class="table-responsive">
        <table class="table jenis-bantuan-table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 48px;">#</th>
                    <th style="width: 120px;">Kode</th>
                    <th>Nama Bantuan</th>
                    <th>Deskripsi</th>
                    <th style="width: 120px;">Status</th>
                    <th style="width: 150px;">Dibuat</th>
                    <th class="text-center" style="width: 160px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisBantuanList as $item)
                    <tr id="row-{{ $item->id }}">
                        <td class="text-muted small">{{ $jenisBantuanList->firstItem() + $loop->index }}</td>
                        <td><span class="fw-bold text-dark">{{ $item->kode }}</span></td>
                        <td class="fw-medium text-dark">{{ $item->nama_bantuan }}</td>
                        <td class="text-muted small text-truncate" style="max-width: 250px;">
                            {{ $item->deskripsi ?? '-' }}
                        </td>
                        <td>
                            @if($item->status)
                                <span class="status-badge" style="background:#dcfce7;color:#16a34a;">
                                    <i class="bi bi-check-circle-fill"></i> Aktif
                                </span>
                            @else
                                <span class="status-badge" style="background:#fee2e2;color:#dc2626;">
                                    <i class="bi bi-x-circle-fill"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button"
                                        class="btn btn-sm btn-icon-action btn-outline-secondary"
                                        title="Detail"
                                        onclick="openDetailModal(this)"
                                        data-kode="{{ $item->kode }}"
                                        data-nama="{{ $item->nama_bantuan }}"
                                        data-deskripsi="{{ $item->deskripsi ?? '' }}"
                                        data-status="{{ $item->status ? '1' : '0' }}"
                                        data-created="{{ $item->created_at->format('d F Y, H:i') }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-sm btn-icon-action btn-outline-primary"
                                        title="Edit"
                                        onclick="openEditModal(this)"
                                        data-id="{{ $item->id }}"
                                        data-kode="{{ $item->kode }}"
                                        data-nama="{{ $item->nama_bantuan }}"
                                        data-deskripsi="{{ $item->deskripsi ?? '' }}"
                                        data-status="{{ $item->status ? '1' : '0' }}"
                                        data-update-url="{{ route('jenis-bantuan.update', $item) }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if(auth()->user()->role === 'super_admin')
                                    <form action="{{ route('jenis-bantuan.destroy', $item) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-sm btn-icon-action btn-outline-danger btn-delete"
                                                data-name="{{ $item->nama_bantuan }}"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-5">
                            <x-empty-state
                                title="Belum ada jenis bantuan"
                                description="Klik tombol 'Tambah Jenis Bantuan' untuk menambahkan jenis bantuan sosial baru."
                                icon="bi-gift" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($jenisBantuanList->hasPages())
        <div class="px-4 py-3 border-top">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <p class="text-muted small mb-0">
                    Menampilkan {{ $jenisBantuanList->firstItem() }}–{{ $jenisBantuanList->lastItem() }} dari {{ $jenisBantuanList->total() }} jenis bantuan
                </p>
                {{ $jenisBantuanList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

{{-- ═══════════════════════════ MODAL: CREATE / EDIT FORM ═══════════════════════════ --}}
<div class="modal fade" id="modalForm" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-saas">
            <div class="modal-header modal-header-saas">
                <h5 class="modal-title" id="modalFormTitle">
                    <i class="bi bi-plus-circle"></i> Tambah Jenis Bantuan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formJenisBantuan" novalidate>
                <div class="modal-body modal-body-saas">

                    <div class="alert alert-danger d-none" id="formGeneralError"></div>

                    {{-- Kode --}}
                    <div class="mb-3">
                        <label for="kode" class="form-label fw-medium">Kode <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode" name="kode"
                               placeholder="Masukkan kode jenis bantuan (misal: PKH, BPNT)">
                        <div class="invalid-feedback" data-error-for="kode"></div>
                    </div>

                    {{-- Nama Bantuan --}}
                    <div class="mb-3">
                        <label for="nama_bantuan" class="form-label fw-medium">Nama Bantuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_bantuan" name="nama_bantuan"
                               placeholder="Masukkan nama jenis bantuan">
                        <div class="invalid-feedback" data-error-for="nama_bantuan"></div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-medium">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4"
                                  placeholder="Masukkan deskripsi singkat jenis bantuan"></textarea>
                        <div class="invalid-feedback" data-error-for="deskripsi"></div>
                    </div>

                    {{-- Status --}}
                    <div class="mb-1">
                        <label for="status" class="form-label fw-medium">Status <span class="text-danger">*</span></label>
                        <select class="form-select" id="status" name="status">
                            <option value="" disabled selected>-- Pilih Status --</option>
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                        <div class="invalid-feedback" data-error-for="status"></div>
                    </div>

                </div>
                <div class="modal-footer modal-footer-saas">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-2" id="btnSubmitForm">
                        <i class="bi bi-check-lg"></i> <span id="btnSubmitFormLabel">Simpan Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════ MODAL: DETAIL ═══════════════════════════ --}}
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-saas">
            <div class="modal-header modal-header-saas">
                <h5 class="modal-title"><i class="bi bi-gift-fill"></i> Detail Jenis Bantuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-saas">
                <div class="detail-band">
                    <div class="icon-circle">
                        <i class="bi bi-gift-fill fs-3 text-success"></i>
                    </div>
                    <div>
                        <span class="badge bg-white text-success fw-bold px-3 py-1 mb-1 fs-6" id="detailKodeBadge"></span>
                        <h5 class="text-white fw-bold mb-0" id="detailNama"></h5>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <p class="detail-field-label mb-1">Kode Bantuan</p>
                        <p class="detail-field-value mb-0" id="detailKode"></p>
                    </div>
                    <div class="col-sm-6">
                        <p class="detail-field-label mb-1">Status</p>
                        <p class="mb-0" id="detailStatus"></p>
                    </div>
                    <div class="col-12">
                        <p class="detail-field-label mb-1">Deskripsi</p>
                        <div class="detail-desc-box" id="detailDeskripsi"></div>
                    </div>
                    <div class="col-12">
                        <p class="detail-field-label mb-1">Tanggal Dibuat</p>
                        <p class="detail-field-value mb-0" id="detailCreated"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer-saas">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary px-4 d-flex align-items-center gap-2" id="detailEditBtn">
                    <i class="bi bi-pencil"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const modalFormEl   = document.getElementById('modalForm');
    const modalDetailEl = document.getElementById('modalDetail');
    const bsModalForm    = new bootstrap.Modal(modalFormEl);
    const bsModalDetail  = new bootstrap.Modal(modalDetailEl);

    const form            = document.getElementById('formJenisBantuan');
    const formTitle       = document.getElementById('modalFormTitle');
    const btnSubmit        = document.getElementById('btnSubmitForm');
    const btnSubmitLabel   = document.getElementById('btnSubmitFormLabel');
    const generalErrorBox  = document.getElementById('formGeneralError');

    const STORE_URL = "{{ route('jenis-bantuan.store') }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')
        ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        : "{{ csrf_token() }}";

    function clearFormErrors() {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('[data-error-for]').forEach(el => el.textContent = '');
        generalErrorBox.classList.add('d-none');
        generalErrorBox.textContent = '';
    }

    function resetForm() {
        form.reset();
        clearFormErrors();
        delete form.dataset.mode;
        delete form.dataset.updateUrl;
    }

    function openCreateModal() {
        resetForm();
        form.dataset.mode = 'create';
        formTitle.innerHTML = '<i class="bi bi-plus-circle"></i> Tambah Jenis Bantuan';
        btnSubmitLabel.textContent = 'Simpan Data';
        bsModalForm.show();
    }

    function openEditModal(btn) {
        resetForm();
        form.dataset.mode = 'edit';
        form.dataset.updateUrl = btn.dataset.updateUrl;

        document.getElementById('kode').value = btn.dataset.kode;
        document.getElementById('nama_bantuan').value = btn.dataset.nama;
        document.getElementById('deskripsi').value = btn.dataset.deskripsi;
        document.getElementById('status').value = btn.dataset.status;

        formTitle.innerHTML = '<i class="bi bi-pencil-square"></i> Edit Jenis Bantuan';
        btnSubmitLabel.textContent = 'Simpan Perubahan';

        bsModalDetail.hide();
        bsModalForm.show();
    }

    let currentDetailEditBtnData = null;

    function openDetailModal(btn) {
        document.getElementById('detailKodeBadge').textContent = btn.dataset.kode;
        document.getElementById('detailNama').textContent = btn.dataset.nama;
        document.getElementById('detailKode').textContent = btn.dataset.kode;
        document.getElementById('detailDeskripsi').textContent = btn.dataset.deskripsi || 'Tidak ada deskripsi.';
        document.getElementById('detailCreated').textContent = btn.dataset.created;

        const statusEl = document.getElementById('detailStatus');
        if (btn.dataset.status === '1') {
            statusEl.innerHTML = '<span class="badge bg-success-subtle text-success fs-6 px-3 py-2"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i> Aktif</span>';
        } else {
            statusEl.innerHTML = '<span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;"></i> Nonaktif</span>';
        }

        // find the matching row's edit button so the "Edit" button inside the detail modal works
        const row = btn.closest('tr');
        currentDetailEditBtnData = row ? row.querySelector('[data-update-url]') : null;

        bsModalDetail.show();
    }

    document.getElementById('detailEditBtn').addEventListener('click', function () {
        if (currentDetailEditBtnData) {
            openEditModal(currentDetailEditBtnData);
        }
    });

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        clearFormErrors();

        const mode = form.dataset.mode || 'create';
        const url = mode === 'edit' ? form.dataset.updateUrl : STORE_URL;
        const method = mode === 'edit' ? 'PATCH' : 'POST';

        const payload = {
            kode: document.getElementById('kode').value,
            nama_bantuan: document.getElementById('nama_bantuan').value,
            deskripsi: document.getElementById('deskripsi').value,
            status: document.getElementById('status').value,
        };

        btnSubmit.disabled = true;
        btnSubmit.querySelector('i').classList.remove('bi-check-lg');
        btnSubmit.querySelector('i').classList.add('bi-arrow-repeat');

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                },
                redirect: 'follow',
                body: JSON.stringify(payload),
            });

            const contentType = response.headers.get('content-type') || '';
            const isJson = contentType.includes('application/json');
            const responseData = isJson ? await response.json() : null;

            if (response.status === 422 && responseData?.errors) {
                Object.keys(responseData.errors).forEach(field => {
                    const input = form.querySelector(`[name="${field}"]`);
                    const errBox = form.querySelector(`[data-error-for="${field}"]`);
                    if (input) input.classList.add('is-invalid');
                    if (errBox) errBox.textContent = responseData.errors[field][0];
                });
                return;
            }

            if (!response.ok) {
                if (responseData?.message) {
                    generalErrorBox.textContent = responseData.message;
                } else {
                    generalErrorBox.textContent = `Terjadi kesalahan pada server (${response.status}). Silakan coba lagi.`;
                }
                generalErrorBox.classList.remove('d-none');
                return;
            }

            bsModalForm.hide();
            await Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: mode === 'edit' ? 'Perubahan disimpan!' : 'Data berhasil ditambahkan!',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                customClass: {
                    popup: 'shadow border border-white',
                }
            });
            window.location.reload();

        } catch (err) {
            generalErrorBox.textContent = 'Tidak dapat terhubung ke server. Periksa koneksi Anda.';
            generalErrorBox.classList.remove('d-none');
        } finally {
            btnSubmit.disabled = false;
            btnSubmit.querySelector('i').classList.remove('bi-arrow-repeat');
            btnSubmit.querySelector('i').classList.add('bi-check-lg');
        }
    });

    // ─── Delete confirmation (SweetAlert2) ───
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const name = this.getAttribute('data-name');
            const form = this.closest('form');

            Swal.fire({
                title: 'Hapus Jenis Bantuan?',
                html: `Jenis bantuan <strong>${name}</strong> akan dihapus secara permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush