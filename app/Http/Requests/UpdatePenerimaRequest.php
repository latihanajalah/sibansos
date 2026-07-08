<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePenerimaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return in_array(auth()->user()->role, ['super_admin', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $penerima = $this->route('penerima');
        $id = is_object($penerima) ? $penerima->id : $penerima;

        return [
            'nik'           => ['required', 'numeric', 'digits:16', Rule::unique('penerima', 'nik')->ignore($id)],
            'no_kk'          => ['required', 'numeric', 'digits:16'],
            'nama'          => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'tempat_lahir'  => ['required', 'string', 'max:100'],
            'tanggal_lahir' => ['required', 'date'],
            'alamat'        => ['required', 'string'],
            'rt'            => ['required', 'string', 'max:10'],
            'rw'            => ['required', 'string', 'max:10'],
            'desa'          => ['required', 'string', 'max:100'],
            'kecamatan'     => ['required', 'string', 'max:100'],
            'kabupaten'     => ['required', 'string', 'max:100'],
            'provinsi'      => ['required', 'string', 'max:100'],
            'no_hp'         => ['nullable', 'numeric', 'digits_between:10,15'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nik'           => 'NIK',
            'no_kk'          => 'Nomor KK',
            'nama'          => 'Nama Lengkap',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tempat_lahir'  => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
            'alamat'        => 'Alamat',
            'rt'            => 'RT',
            'rw'            => 'RW',
            'desa'          => 'Desa/Kelurahan',
            'kecamatan'     => 'Kecamatan',
            'kabupaten'     => 'Kabupaten/Kota',
            'provinsi'      => 'Provinsi',
            'no_hp'         => 'Nomor HP',
        ];
    }
}
