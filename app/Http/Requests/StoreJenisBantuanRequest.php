<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreJenisBantuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, ['super_admin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'kode'         => ['required', 'string', 'max:20', 'unique:jenis_bantuan,kode'],
            'nama_bantuan' => ['required', 'string', 'max:100'],
            'deskripsi'    => ['nullable', 'string'],
            'status'       => ['required', Rule::in(['1', '0'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'kode'         => 'Kode',
            'nama_bantuan' => 'Nama Bantuan',
            'deskripsi'    => 'Deskripsi',
            'status'       => 'Status',
        ];
    }
}
