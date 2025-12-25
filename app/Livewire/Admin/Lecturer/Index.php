<?php

namespace App\Livewire\Admin\Lecturer;

use Livewire\Component;
use App\Services\LecturerServices;

class Index extends Component
{
    public $lecturer;
    public $listeners = [
        'loadData' => 'data',
    ];
    
    public function data()
    {
        $service = new LecturerServices();
        $this->lecturer = $service->getLecturer();
    }
    public function mount()
    {
        $this->data();
    }
    public function render()
    {
        return view('livewire.admin.lecturer.index',[
            'lecturers' => $this->lecturer,
        ]);
    }
}
