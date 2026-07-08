<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSurveiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'petugas';
    }

    public function rules(): array
    {
        return [
            // Kondisi Rumah
            'status_rumah'       => ['required', 'string', 'max:100'],
            'kepemilikan_rumah'  => ['required', 'string', 'max:100'],

            // Bangunan
            'jenis_lantai'       => ['required', 'string', 'max:100'],
            'jenis_dinding'      => ['required', 'string', 'max:100'],
            'jenis_atap'         => ['required', 'string', 'max:100'],

            // Penghuni
            'jumlah_kamar'       => ['required', 'integer', 'min:0'],
            'jumlah_penghuni'    => ['required', 'integer', 'min:1'],

            // Ekonomi
            'pekerjaan'          => ['required', 'string', 'max:150'],
            'penghasilan'        => ['required', 'numeric', 'min:0'],
            'jumlah_tanggungan'  => ['required', 'integer', 'min:0'],

            // Kepemilikan aset (checkbox – opsional, jika tidak dicentang = false)
            'punya_motor'        => ['nullable', 'boolean'],
            'punya_mobil'        => ['nullable', 'boolean'],
            'punya_sawah'        => ['nullable', 'boolean'],
            'punya_ternak'       => ['nullable', 'boolean'],

            // Catatan
            'catatan'            => ['nullable', 'string'],

            // Foto – minimal 3 kategori wajib
            'foto'               => ['nullable', 'array'],
            'foto.*'             => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_tampak_depan'  => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_ruang_tamu'    => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_dapur'         => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_kamar'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_kamar_mandi'   => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            // Dokumen – opsional, bisa banyak
            'dokumen'            => ['nullable', 'array'],
            'dokumen.*.file'     => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'dokumen.*.nama'     => ['required', 'string', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'status_rumah.required'      => 'Status rumah wajib diisi.',
            'kepemilikan_rumah.required' => 'Kepemilikan rumah wajib diisi.',
            'jenis_lantai.required'      => 'Jenis lantai wajib diisi.',
            'jenis_dinding.required'     => 'Jenis dinding wajib diisi.',
            'jenis_atap.required'        => 'Jenis atap wajib diisi.',
            'jumlah_kamar.required'      => 'Jumlah kamar wajib diisi.',
            'jumlah_penghuni.required'   => 'Jumlah penghuni wajib diisi.',
            'pekerjaan.required'         => 'Pekerjaan wajib diisi.',
            'penghasilan.required'       => 'Penghasilan wajib diisi.',
            'jumlah_tanggungan.required' => 'Jumlah tanggungan wajib diisi.',
            'foto_tampak_depan.required' => 'Foto tampak depan rumah wajib diupload.',
            'foto_ruang_tamu.required'   => 'Foto ruang tamu wajib diupload.',
            'foto_dapur.required'        => 'Foto dapur wajib diupload.',
            'foto_tampak_depan.image'    => 'Foto tampak depan harus berupa gambar (jpg/jpeg/png).',
            'foto_ruang_tamu.image'      => 'Foto ruang tamu harus berupa gambar (jpg/jpeg/png).',
            'foto_dapur.image'           => 'Foto dapur harus berupa gambar (jpg/jpeg/png).',
            'foto_tampak_depan.max'      => 'Foto tampak depan maksimal 2 MB.',
            'foto_ruang_tamu.max'        => 'Foto ruang tamu maksimal 2 MB.',
            'foto_dapur.max'             => 'Foto dapur maksimal 2 MB.',
        ];
    }
}
