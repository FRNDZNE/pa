<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KelasRequest extends FormRequest
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
            'lecture_id' => 'required|exists:lecturers,id',
            'name' => 'required',
        ];
    }

    public function attributes() : array
    {
        return [
            'lecture_id' => 'Dosen Pengampu',
            'name' => 'Nama Kelas',
        ];
    }

    public function messages() : array
    {
        return [
            'required' => ':attribute Tidak Boleh Kosong',
            'exists' => ':attribute Tidak Ditemukan',
        ];
    }
}
