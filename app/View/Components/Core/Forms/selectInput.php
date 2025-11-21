<?php

namespace App\View\Components\Core\Forms;

use Illuminate\View\Component;
use function view;

class selectInput extends Component
{

    public $label;
    public $class;
    public $options;
    public $name;
    public $required;
    public $multiple;
    public $placeholder;
    public $text;
    public $selected;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $name, $options, $class = null, $required = 0, $multiple = 0, $placeholder = null, $text = null, $selected = null)
    {
        $this->label = $label;
        $this->name = $name;
        $this->options = $options;
        $this->class = $class;
        $this->required = $required;
        $this->multiple = $multiple;
        $this->placeholder = $placeholder;
        $this->text = $text;
        $this->selected = $selected;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.select-input');
    }
}
