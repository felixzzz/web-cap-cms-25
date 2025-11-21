<?php

namespace App\View\Components\Core\Forms;

use Illuminate\View\Component;
use function view;

class tinymceEditor extends Component
{
    public $name;
    public $class;
    public $label;
    public $required;
    public $value;
    public $text;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $text = null, $class = null, $required = 0, $value = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->class = $class;
        $this->required = $required;
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
        return view('components.forms.tinymce-editor');
    }
}
