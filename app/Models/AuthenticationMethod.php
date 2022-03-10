<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthenticationMethod extends Model
{
    //
	protected $table="authentication_method";
	protected $fillable = [
		"method","active"
	];
	public $timestamps = false;
}
