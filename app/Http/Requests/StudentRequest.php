<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
        $user = $this->route('user');

        $this->errorBag = $isPatch
            ? 'edit_'.$user?->uuid
            : 'create';

        return [
            'student_number' => [
                'required',
                Rule::unique('students', 'student_number')
                    ->ignore($user?->student?->id),
            ],
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($user?->id),
            ],
            'password' => $isPatch ? 'nullable' : 'required',
        ];
    }

    public function attributes() : array
    {
        return [
            'name' => 'Nama',
            'email' => 'E-Mail',
            'student_number' => 'NIM Mahasiswa',
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
