<?php

namespace App\Livewire\Admin\Lecturer;

use Livewire\Component;
use App\Services\AdminLecturerServices as LecturerService;

class GetData extends Component
{
    protected $lecturers;
    protected $listeners = [
        'lecturerCreated' => 'loadData',
    ];
    // protected $listeners = [
    //     'lecturerCreated' => 'loadData',
    //     'lecturerUpdated' => 'loadData',
    //     'lecturerDeleted' => 'loadData',
    // ];


    public function loadData(LecturerService $service)
    {
        $this->lecturers = $service->getData();
        $this->dispatch('refreshDatatable');
    }
    
    // Eksekusi Komponen Pertama
    public function mount(LecturerService $service)
    {
        $this->loadData($service);
    }

    public function render()
    {
        return view('livewire.admin.lecturer.get-data',[
            'lecturers' => $this->lecturers,
        ]);
    }
}
