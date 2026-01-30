<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LecturerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    protected $errorBag;

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
            'lecture_number' => [
                'required',
                Rule::unique('lecturers', 'lecture_number')
                    ->ignore($user?->lecturer?->id),
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
