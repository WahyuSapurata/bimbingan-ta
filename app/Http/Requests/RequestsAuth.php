<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestsAuth extends FormRequest
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
            'username' => 'required',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Kolom username harus di isi',
            'password.required' => 'Kolom password harus di isi'
        ];
    }

    public function getCredentials()
    {
        $username = $this->get('username');

        if ($username) {
            return [
                'username' => $username,
                'password' => $this->get('password')
            ];
        }

        return $this->only('username', 'password');
    }
}
