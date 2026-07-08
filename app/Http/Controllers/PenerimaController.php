<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Penerima;
use App\Http\Requests\StorePenerimaRequest;
use App\Http\Requests\UpdatePenerimaRequest;
use Illuminate\Http\Request;

class PenerimaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penerima::latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%");
            });
        }

        $penerimaList = $query->paginate(10)->withQueryString();

        return view('penerima.index', compact('penerimaList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penerima.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenerimaRequest $request)
    {
        $penerima = Penerima::create($request->validated());

        ActivityLogger::log("Tambah Penerima: {$penerima->nama} (NIK: {$penerima->nik})");

        return redirect()->route('penerima.index')
            ->with('success', 'Data penerima berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penerima $penerima)
    {
        return view('penerima.show', compact('penerima'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penerima $penerima)
    {
        return view('penerima.edit', compact('penerima'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePenerimaRequest $request, Penerima $penerima)
    {
        $penerima->update($request->validated());

        ActivityLogger::log("Edit Penerima: {$penerima->nama} (NIK: {$penerima->nik})");

        return redirect()->route('penerima.index')
            ->with('success', 'Data penerima berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penerima $penerima)
    {
        // Check if already used in pengajuan relation
        if ($penerima->pengajuan()->exists()) {
            return redirect()->route('penerima.index')
                ->with('error', 'Data penerima tidak dapat dihapus karena sudah memiliki riwayat pengajuan.');
        }

        $nama = $penerima->nama;
        $nik = $penerima->nik;
        $penerima->delete();

        ActivityLogger::log("Hapus Penerima: {$nama} (NIK: {$nik})");

        return redirect()->route('penerima.index')
            ->with('success', 'Data penerima berhasil dihapus.');
    }
}
