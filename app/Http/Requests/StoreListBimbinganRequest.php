<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreListBimbinganRequest extends FormRequest
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
            'uuid_mahasiswa' => 'required',
            'angkatan' => 'required',
            'judul' => 'required',
            'jenis_bimbingan' => 'required',
            'pembimbing' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'uuid_dosen.required' => 'Kolom nama dosen harus di isi.',
            'uuid_mahasiswa.required' => 'Kolom nama mahasiswa harus di isi.',
            'angkatan.required' => 'Kolom angkatan harus di isi.',
            'judul.required' => 'Kolom judul harus di isi.',
            'jenis_bimbingan.required' => 'Kolom jenis bimbingan harus di isi.',
            'pembimbing.required' => 'Kolom pembimbing harus di isi.',
        ];
    }
}
