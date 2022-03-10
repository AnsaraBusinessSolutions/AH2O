<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SideBarSubMenuList extends Model
{
    //
	protected $table = "sidebar_submenu_list";
	protected $fillable = [
		"menu_id","submenu","position"
	];
	public $timestamps = true;
}
