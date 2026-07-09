<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveiRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var \App\Models\Survei $survei */
        $survei = $this->route('survei');

        $editableStatuses = ['menunggu_verifikasi', 'revisi_survei'];

        // Only the owning petugas can update, and only in editable statuses
        return auth()->user()->role === 'petugas'
            && $survei->pengajuan->petugas_id === auth()->id()
            && in_array($survei->pengajuan->status, $editableStatuses);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('penghasilan')) {
            $this->merge([
                'penghasilan' => $this->normalizeCurrencyValue($this->input('penghasilan')),
            ]);
        }
    }

    private function normalizeCurrencyValue($value): string
    {
        $raw = preg_replace('/[^\d.]/', '', (string) $value);

        if (preg_match('/\.(\d{2})$/', $raw)) {
            $raw = preg_replace('/\.(\d{2})$/', '', $raw);
        }

        return preg_replace('/\D/', '', $raw);
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

            // Kepemilikan aset
            'punya_motor'        => ['nullable', 'boolean'],
            'punya_mobil'        => ['nullable', 'boolean'],
            'punya_sawah'        => ['nullable', 'boolean'],
            'punya_ternak'       => ['nullable', 'boolean'],

            // Catatan
            'catatan'            => ['nullable', 'string'],

            // Foto baru (opsional saat edit – existing photos tetap ada)
            'foto_tampak_depan'  => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_ruang_tamu'    => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_dapur'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_kamar'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'foto_kamar_mandi'   => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],

            // Dokumen baru
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
        ];
    }
}
