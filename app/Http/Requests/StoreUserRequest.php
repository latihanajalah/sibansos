<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->role === 'super_admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['required', 'string', 'max:20'],
            'role' => ['required', Rule::in(['super_admin', 'admin', 'petugas', 'pimpinan'])],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }
}
