<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiskusiRequest extends FormRequest
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
            'uuid_mahasiswa' => 'required',
            'judul' => 'required',
            'kategori' => 'required',
            'deskripsi' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'uuid_mahasiswa.required' => 'Kolom nama mahasiswa harus di isi.',
            'judul.required' => 'Kolom judul diskusi harus di isi.',
            'kategori.required' => 'Kolom kategori harus di isi.',
            'deskripsi.required' => 'Kolom deskripsi harus di isi.',
        ];
    }
}
