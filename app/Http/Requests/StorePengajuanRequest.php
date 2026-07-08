<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePengajuanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role === 'petugas';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'penerima_id'        => ['required', 'integer', 'exists:penerima,id'],
            'jenis_bantuan_ids'  => ['required', 'array', 'min:1'],
            'jenis_bantuan_ids.*'=> ['integer', 'exists:jenis_bantuan,id'],
            'keterangan'         => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'penerima_id'       => 'Penerima Bantuan',
            'jenis_bantuan_ids' => 'Jenis Bantuan',
            'keterangan'        => 'Keterangan',
        ];
    }
}
