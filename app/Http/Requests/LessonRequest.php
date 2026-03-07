<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
{
    protected $errorBag;

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $isPatch = $this->isMethod('PATCH');
        $lesson = $this->route('lessons');

        $this->errorBag = $isPatch ? 'edit_'. $lesson?->uuid : 'create';
        return [
            'lecturer_id' => 'required|exists:lecturers,id|not_in:0',
            'title' => 'required',
        ];
    }

    public function attributes() : array
    {
        return [
            'lecturer_id' => 'Dosen Pengampu',
            'title' => 'Nama Materi',
        ];
    }

    public function messages() : array
    {
        return [
            'required' => ':attribute Tidak Boleh Kosong',
            'exists' => ':attribute Tidak Ditemukan',
            'not_in' => ':attribute Harus Dipilih',
        ];
    }
}
    