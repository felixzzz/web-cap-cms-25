<?php

namespace App\View\Components\Core\Forms;

use Illuminate\View\Component;
use function view;

class textareaInput extends Component
{
    public $name;
    public $class;
    public $label;
    public $required;
    public $placeholder;
    public $text;
    public $value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $required = 0, $placeholder = null, $text = null, $class = null, $value = null)
    {
        $this->name = $name;
        $this->class = $class;
        $this->label = $label;
        $this->required = $required;
        $this->placeholder = $placeholder;
        $this->text = $text;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.textarea-input');
    }
}
