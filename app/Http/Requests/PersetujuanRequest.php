<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersetujuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role === 'pimpinan';
    }

    public function rules(): array
    {
        $keputusan = $this->input('keputusan');

        return [
            'keputusan' => ['required', Rule::in(['setujui', 'tolak'])],
            'catatan'   => [
                Rule::requiredIf(fn () => $keputusan === 'tolak'),
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'keputusan.required' => 'Keputusan persetujuan wajib dipilih.',
            'keputusan.in'       => 'Keputusan tidak valid.',
            'catatan.required'   => 'Alasan penolakan wajib diisi jika Anda menolak pengajuan ini.',
        ];
    }
}
