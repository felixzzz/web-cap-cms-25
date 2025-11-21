<?php

namespace App\View\Components\Core\Forms;

use Illuminate\View\Component;
use function view;

class imageInput extends Component
{

    public $name;
    public $label;
    public $src;
    public $required;
    public $text;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($name, $label, $src = null, $required = 0, $text = null)
    {
        $this->name = $name;
        $this->label = $label;
        $this->src = $src;
        $this->text = $text;
        $this->required = $required;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.image-input');
    }
}
