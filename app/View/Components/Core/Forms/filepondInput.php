<?php

namespace App\View\Components\Core\Forms;

use Illuminate\View\Component;
use function view;

class filepondInput extends Component
{
    public $name;
    public $class;
    public $label;
    public $required;
    public $text;
    public $src;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $required = 0, $class = null, $text = null, $src = null)
    {
        $this->name = $name;
        $this->class = $class;
        $this->label = $label;
        $this->required = $required;
        $this->text = $text;
        $this->src = $src;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.filepond-input');
    }
}
