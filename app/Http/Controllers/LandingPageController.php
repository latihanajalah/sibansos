<?php

namespace App\Http\Controllers;

use App\Models\JenisBantuan;
use App\Models\Penerima;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Landing Page – accessible by anyone (no auth required).
     */
    public function index()
    {
        $jenisBantuan = JenisBantuan::where('status', true)->get();

        return view('landing.index', compact('jenisBantuan'));
    }

    /**
     * Cek Status – form input NIK.
     */
    public function cekStatus(Request $request)
    {
        // Jika tidak ada parameter NIK, tampilkan form saja
        if (! $request->filled('nik')) {
            return view('landing.cek-status');
        }

        // Validasi NIK
        $validated = $request->validate([
            'nik' => ['required', 'numeric', 'digits:16'],
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.numeric'  => 'NIK harus berupa angka.',
            'nik.digits'   => 'NIK harus terdiri dari 16 digit.',
        ]);

        $nik = $validated['nik'];

        // Cari penerima berdasarkan NIK
        $penerima = Penerima::where('nik', $nik)->first();

        if (! $penerima) {
            return view('landing.cek-status', [
                'searched' => true,
                'penerima' => null,
                'nik'      => $nik,
            ]);
        }

        // Ambil pengajuan terbaru beserta relasi yang dibutuhkan
        $pengajuan = $penerima->pengajuan()
            ->with([
                'petugas:id,nama',
                'jenisBantuan:id,kode,nama_bantuan,deskripsi',
                'riwayatStatus' => fn ($q) => $q->with('user:id,nama')->orderBy('created_at', 'asc'),
                'dokumen'       => fn ($q) => $q->where('nama_dokumen', 'Bukti Penyaluran'),
                'penyaluran'    => fn ($q) => $q->latest()->limit(1),
            ])
            ->latest()
            ->first();

        return view('landing.cek-status', [
            'searched'  => true,
            'penerima'  => $penerima,
            'pengajuan' => $pengajuan,
            'nik'       => $nik,
        ]);
    }
}
