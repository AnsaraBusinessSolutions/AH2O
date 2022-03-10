<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
class UserController extends Controller
{
    //
	public function __construct(){
	
	}

	public function createUser(){
		return view("user.create");
	}
	public function createUserData(Request $request){
		$rules = [
			"name"=>"required",
			"email"=>"required|email",
			"role"=>"required",
			"password"=>"required"
		];
		$validator = \Validator::make($request->all(),$rules);
		if($validator->passes()){
			$data = $request->all();
			$role = Role::select("id")->where("name",$data["role"])->first();
			$data_insert = [
				"name"=>$data["name"],
				"email"=>$data["email"],
				"role"=>$role->id,
				"password"=>bcrypt($data["password"])
			];
			$id = User::insert($data_insert);
			if($id){
				return redirect()->route("home")->with("message","User created successfully");
			}
			else{
				return redirect()->route("home")->with("message","Unable to create user");//'
			}
		}
		else{
			return redirect()->back()->with("message","Please fill all the required fields");
		}

	}
}
