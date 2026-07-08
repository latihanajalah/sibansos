<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePengajuanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $pengajuan = $this->route('pengajuan');
        
        return auth()->user()->role === 'petugas'
            && $pengajuan->petugas_id === auth()->id()
            && $pengajuan->status === 'menunggu_survei';
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
