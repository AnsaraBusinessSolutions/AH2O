<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class PassportAuthControl extends Controller
{
    
    //
	public function __construct(){
	
	}
	
	public function login(Request $request)
    	{
        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->guard("api-passport")->attempt($loginData)) {
            return response(['id'=>2,'message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['id'=>'1','user' => auth()->user(), 'access_token' => $accessToken, 'location'=>".".RouteServiceProvider::HOME]);

	//public function login(Request $requesuse Laravel\Passport\Passport;t){
	
	}
	
	public function register(Request $request)
    	{
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed'
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['id'=>1, 'user' => $user, 'access_token' => $accessToken]);
    	}
	
	public function user(Request $request){
		return response()->json(["user"=>auth("api-passport")->user(), "message"=>"successfull"]);	
	}	
}
