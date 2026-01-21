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
        $isPatch = $this->isMethod('PATCH');
        return [
            'lecture_number' => 'required|unique:lecturers,lecture_number,' . $this->lecturer?->id,
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $this->user?->id,
            'password' => $isPatch ? 'sometimes|min:8' : 'required|min:8',
        ];
    }

    public function attributes() : array
    {
        return [
            'name' => 'Nama',
            'email' => 'E-Mail',
            'lecture_number' => 'NIDN Dosen',
            'password' => 'Kata Sandi',
        ];
    }

    public function messages() : array
    {
        return [
            'required' => ':attribute Tidak Boleh Kosong',
            'unique' => ':attribute Sudah Digunakan',
            'email' => ':attribute Harus Berupa Email yang Valid',
            'min' => ':attribute Minimal :min Karakter',
        ];
    }
}
