<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AnchorButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
	public $href = "";
	public $class = "";
	public $content = "";
    public function __construct($href="",$class="",$content="")
    {
        //
    	$this->href=$href;
	$this->class=$class;
	$this->content = $content;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.anchor-button');
    }
}
