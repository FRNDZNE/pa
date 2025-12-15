<?php

namespace App\Livewire\Admin\Lecturer;

use Livewire\Component;
use App\Services\LecturerServices;

class Index extends Component
{
    public $lecturer;
    public $listeners = [
        'loadData' => 'refreshData'
    ];
    
    public function refreshData()
    {
        $service = new LecturerServices();
        $this->lecturer = $service->getLecturer();
    }
    public function mount()
    {
        $service = new LecturerServices();
        $this->lecturer = $service->getLecturer();
    }
    public function render()
    {
        return view('livewire.admin.lecturer.index',[
            'lecturers' => $this->lecturer,
        ]);
    }
}
