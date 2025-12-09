<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Modal extends Component
{
    public $modalId = 'globalModal';
    public $title;
    public $size = 'md';

    protected $listeners = ['openModal', 'closeModal'];

    public function openModal($title = null)
    {
        $this->title = $title;
        $this->dispatch('open-modal', modalId: $this->modalId);
    }

    public function closeModal()
    {
        $this->dispatch('close-modal', modalId: $this->modalId);
    }
    
    public function render()
    {
        return view('livewire.components.modal');
    }
}
