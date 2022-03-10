<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputHtml extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name;
    public $class;
    public $keypress;public $type;
    public $id;
    
    public function __construct($name,$class,$type,$id,$keypress)
    {
        //
        $this->name = $name;
        $this->class = $class;
        $this->keypress = $keypress;
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.input-html');
    }
}
