<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Register extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'username' => 'required',
            'nip_nim' => 'required',
            'email' => 'required',
            'role' => 'required',
            'password' => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Kolom nama harus di isi.',
            'username.required' => 'Kolom username harus di isi.',
            'nip_nim.required' => 'Kolom nip/nim harus di isi.',
            'email.required' => 'Kolom email harus di isi.',
            'role.required' => 'Kolom role harus di isi.',
            'password.required' => 'Kolom password harus di isi.',
            'password.min' => 'Password harus 8 karakter',
        ];
    }
}
