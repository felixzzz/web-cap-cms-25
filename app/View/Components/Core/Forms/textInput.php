<?php

namespace App\View\Components\Core\Forms;

use Illuminate\View\Component;
use function view;

class textInput extends Component
{
    public $name;
    public $label;
    public $required;
    public $placeholder;
    public $text;
    public $value;
    public $isSide;
    public $type;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $type = 'text', $required = 0, $placeholder = null, $text = null, $value = null, $isSide = 0)
    {
        $this->name = $name;
        $this->label = $label;
        $this->required = $required;
        $this->placeholder = $placeholder;
        $this->text = $text;
        $this->value = $value;
        $this->isSide = $isSide;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.text-input');
    }
}
