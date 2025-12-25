<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LecturerRequest extends FormRequest
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
            'lecture_number' => 'required|unique:lecturers,lecture_number,' . $this->lecturer?->id,
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user?->id,
        ];
    }

    public function attributes() : array
    {
        return [
            'name' => 'Tanggal',
            'lecture' => 'Aktivitas',
            'output' => 'Hasil',
        ];
    }

    public function messages() : array
    {
        return [
            'required' => ':attribute Tidak Boleh Kosong',
        ];
    }
}
