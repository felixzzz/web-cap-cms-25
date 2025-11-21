<?php

namespace App\View\Components\Core\Forms\Checkbox;

use Illuminate\View\Component;
use function view;

class singleCheckbox extends Component
{
    public $name;
    public $text;
    public $label;
    public $checked;
    public $required;
    public $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $value = null, $text = null, $checked = 0, $required = 0)
    {
        $this->name = $name;
        $this->label = $label;
        $this->text = $text;
        $this->checked = $checked;
        $this->required = $required;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.checkbox.single-checkbox');
    }
}
