<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
	public $sideMenu;
	public $sideSubMenu;

    public function __construct($sideMenu)
    {
        //
	    $this->sideMenu = $sideMenu;
	//$this->sideSubMenu = $sideSubMenu;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.sidebar');
    }
}
