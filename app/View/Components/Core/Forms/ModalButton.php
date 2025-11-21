<?php

namespace App\View\Components\Core\Forms;

use Illuminate\View\Component;

class ModalButton extends Component
{
    protected $idModal;
    protected $label;
    protected $title;
    protected $class;
    protected $description;
    protected $icon;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($idModal, $label, $title, $class = null, $description = null, $icon = null)
    {
        $this->idModal = $idModal;
        $this->label = $label;
        $this->title = $title;
        $this->class = $class;
        $this->description = $description;
        $this->icon = $icon;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.core.forms.modal-button');
    }
}
