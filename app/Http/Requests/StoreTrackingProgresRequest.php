<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrackingProgresRequest extends FormRequest
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
            'progres' => 'required',
            'target' => 'required',
            'catatan' => 'required',
            'feedback' => 'required',
            'konsultasi' => 'required',
            'ttd' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'progres.required' => 'Kolom progres harus di isi.',
            'target.required' => 'Kolom target harus di isi.',
            'catatan.required' => 'Kolom catatan harus di isi.',
            'feedback.required' => 'Kolom feedback harus di isi.',
            'konsultasi.required' => 'Kolom kusultasi harus di isi.',
            'ttd.required' => 'Kolom ttd harus di isi.',
        ];
    }
}
