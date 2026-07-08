<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJenisBantuanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, ['super_admin', 'admin']);
    }

    public function rules(): array
    {
        $jenisBantuan = $this->route('jenis_bantuan');
        $id = is_object($jenisBantuan) ? $jenisBantuan->id : $jenisBantuan;

        return [
            'kode'         => ['required', 'string', 'max:20', Rule::unique('jenis_bantuan', 'kode')->ignore($id)],
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
