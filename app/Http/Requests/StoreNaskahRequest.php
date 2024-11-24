<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNaskahRequest extends FormRequest
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
            'uuid_dosen' => 'required',
            'judul' => 'required',
            'deskripsi' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'uuid_dosen.required' => 'Kolom nama dosen harus di isi.',
            'judul.required' => 'Kolom judul dokumen harus di isi.',
            'deskripsi.required' => 'Kolom deskripsi harus di isi.',
        ];
    }
}
