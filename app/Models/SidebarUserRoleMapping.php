<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SidebarUserRoleMapping extends Model
{
    //
	protected $table = "sidebar_userroles_mapping";
	protected $fillable = ["menu_id","role"];
	public $timeStamps=false;
}
