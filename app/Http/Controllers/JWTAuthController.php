<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JWTAuthController extends Controller
{
    //
    public function __construct(){

    }
    public function login(Request $request){
        $rules = [
            "email"=>"required|email",
            "password"=>"required"
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){
        if(!$token = Auth::guard('api')->attempt($loginData)){
            return response()->json(["id"=>2,"error"=>"User credentials are invalid"],400);
        }
        else{
            return response()->json(["id"=>1,"token"=> $this->createNewToken($token),"message"=>"Login Successfull","location"=>".".RouteServiceProvider::HOME],200);
        }

        }
        else{
            return response()->json(["id"=>2,"message"=>"Invalid Credentials or details"],400);
        }
    }
    public function createNewToken($token){
        return response()->json(["access_token"=>$token, "token_type"=>"Bearer","expires_in"=>auth()->guard("api")->factory()->getTTL() * 60 ]);
    }
}
