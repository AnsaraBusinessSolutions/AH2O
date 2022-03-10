<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    //
    public function __construct(){

    }
    public function index(Request $request){
        $html = view("components.button",["name"=>"Settings","class"=>"btn btn-primary btn-large","content"=>"This is the button from the settings page."])->render();
        return response()->json(["id"=>1,"html"=>$html,"result"=>"success"]);
    }
}
