<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SidebarMenusList extends Model
{
    //
	protected $table = "sidebar_menu_list";

	protected $fillable = [
		"menu","is_this_submenu" ,"position"
	];

	public $timestamps = true;
}
