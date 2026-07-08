<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\JenisBantuan;
use App\Http\Requests\StoreJenisBantuanRequest;
use App\Http\Requests\UpdateJenisBantuanRequest;
use Illuminate\Http\Request;

class JenisBantuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = JenisBantuan::latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama_bantuan', 'like', "%{$search}%");
            });
        }

        $jenisBantuanList = $query->paginate(10)->withQueryString();

        return view('jenis-bantuan.index', compact('jenisBantuanList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis-bantuan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJenisBantuanRequest $request)
    {
        $jenisBantuan = JenisBantuan::create($request->validated());

        ActivityLogger::log("Tambah Jenis Bantuan: {$jenisBantuan->nama_bantuan} ({$jenisBantuan->kode})");

        return redirect()->route('jenis-bantuan.index')
            ->with('success', 'Jenis bantuan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisBantuan $jenisBantuan)
    {
        return view('jenis-bantuan.show', compact('jenisBantuan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisBantuan $jenisBantuan)
    {
        return view('jenis-bantuan.edit', compact('jenisBantuan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJenisBantuanRequest $request, JenisBantuan $jenisBantuan)
    {
        $jenisBantuan->update($request->validated());

        ActivityLogger::log("Edit Jenis Bantuan: {$jenisBantuan->nama_bantuan} ({$jenisBantuan->kode})");

        return redirect()->route('jenis-bantuan.index')
            ->with('success', 'Jenis bantuan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisBantuan $jenisBantuan)
    {
        // Only super_admin can delete
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang dapat menghapus jenis bantuan.');
        }

        // Check if already used in pengajuan_bantuan relation
        if ($jenisBantuan->pengajuan()->exists()) {
            return redirect()->route('jenis-bantuan.index')
                ->with('error', 'Jenis bantuan tidak dapat dihapus karena sudah digunakan pada data pengajuan.');
        }

        $nama = $jenisBantuan->nama_bantuan;
        $kode = $jenisBantuan->kode;
        $jenisBantuan->delete();

        ActivityLogger::log("Hapus Jenis Bantuan: {$nama} ({$kode})");

        return redirect()->route('jenis-bantuan.index')
            ->with('success', 'Jenis bantuan berhasil dihapus.');
    }
}
