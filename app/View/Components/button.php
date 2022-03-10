<?php

namespace App\View\Components;

use Illuminate\View\Component;

class button extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
	public $name="";
	public $class="";
	public $content = "";
    public function __construct($nameButton="",$class="",$content)
    {
        //
	    $this->name = $nameButton;
	    $this->class = $class;
	    $this->content = $content;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.button');
    }
}
