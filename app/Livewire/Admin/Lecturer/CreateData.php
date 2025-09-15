<?php

namespace App\Livewire\Admin\Lecturer;

use Livewire\Component;
use App\Services\AdminLecturerServices as LecturerService;


class CreateData extends Component
{
    public $lecture_number, $name, $email;

    protected $rules = [
        'lecture_number' => 'required|unique:lecturers,lecture_number',
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
    ];

    protected $messages = [
        'lecture_number.required' => 'NIP Harus diisi!.',
        'lecture_number.unique' => 'NIP sudah terdaftar!.',
        'name.required' => 'Nama harus diisi!.',
        'email.required' => 'Email harus diisi!.',
        'email.email' => 'Format email tidak valid!.',
        'email.unique' => 'Email sudah terdaftar!.',
    ];

    public function storeData(LecturerService $services)
    {
        $this->validate();
        $data = [
            'lecture_number' => $this->lecture_number,
            'name' => $this->name,
            'email' => $this->email,
        ];
        try {
            $services->storeData($data);
            $this->reset();
            
            $this->dispatch('closeModal');
            $this->dispatch('lecturerCreated');
            session()->flash('success', 'Data Berhasil Dibuat!');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal Membuat Data: ' . $e->getMessage());   
        }
    }

    public function render()
    {
        return view('livewire.admin.lecturer.create-data');
    }
}
