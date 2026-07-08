<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenyaluranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'petugas';
    }

    public function rules(): array
    {
        return [
            'pengajuan_id' => ['required', 'exists:pengajuan,id'],
            'tanggal'      => ['required', 'date'],
            'catatan'      => ['nullable', 'string', 'max:1000'],
            'bukti'        => ['required', 'array', 'min:1'],
            'bukti.*'      => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'pengajuan_id.required' => 'Pengajuan wajib dipilih.',
            'pengajuan_id.exists'   => 'Pengajuan tidak valid.',
            'tanggal.required'      => 'Tanggal penyaluran wajib diisi.',
            'tanggal.date'          => 'Format tanggal tidak valid.',
            'bukti.required'        => 'Bukti penyaluran wajib diupload minimal 1 file.',
            'bukti.min'             => 'Bukti penyaluran wajib diupload minimal 1 file.',
            'bukti.*.file'          => 'Bukti penyaluran harus berupa file.',
            'bukti.*.mimes'         => 'Bukti penyaluran harus berformat JPG, JPEG, PNG, atau PDF.',
            'bukti.*.max'           => 'Ukuran file bukti penyaluran maksimal 5 MB.',
        ];
    }
}
