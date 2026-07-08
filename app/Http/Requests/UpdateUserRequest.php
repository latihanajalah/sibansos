<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $user = $this->route('user');
        $userId = is_object($user) ? $user->id : $user;

        return [
            'nama' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'no_hp' => ['required', 'string', 'max:20'],
            'role' => ['required', Rule::in(['super_admin', 'admin', 'petugas', 'pimpinan'])],
            'status' => ['required', Rule::in(['aktif', 'nonaktif'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];
    }
}
