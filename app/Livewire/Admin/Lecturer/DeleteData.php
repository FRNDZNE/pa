<?php

namespace App\Livewire\Admin\Lecturer;

use Livewire\Component;
use App\Services\AdminLecturerServices as LecturerService;

class DeleteData extends Component
{
    public $id;

    public function deleteData($id, LecturerService $services)
    {
        try {
            $services->deleteDataLecturer($id);
            session()->flash('message', 'Lecturer deleted successfully.');
            $this->dispatch('lecturerDeleted');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function render()
    {
        return view('livewire.admin.lecturer.delete-data');
    }
}
