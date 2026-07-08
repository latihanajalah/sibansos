<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VerifikasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, ['super_admin', 'admin']);
    }

    public function rules(): array
    {
        $keputusan = $this->input('keputusan');

        return [
            'keputusan' => ['required', Rule::in(['setujui', 'revisi', 'tolak'])],
            'catatan'   => [
                Rule::requiredIf(fn () => in_array($keputusan, ['revisi', 'tolak'])),
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'keputusan.required' => 'Keputusan verifikasi wajib dipilih.',
            'keputusan.in'       => 'Keputusan tidak valid.',
            'catatan.required'   => 'Catatan wajib diisi untuk keputusan Revisi atau Tolak.',
        ];
    }
}
