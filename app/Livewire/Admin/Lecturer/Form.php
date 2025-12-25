<?php

namespace App\Livewire\Admin\Lecturer;

use Livewire\Component;
use App\Services\LecturerServices;

class Form extends Component
{
    public $name;
    public $email;
    public $lecture_number;

    public function store()
    {

    }

    public function update()
    {
        
    }

    public function render()
    {
        return view('livewire.admin.lecturer.form');
    }
}
