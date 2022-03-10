<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThemesController extends Controller
{
    //
    public function __construct(){

    }
    public function index(Request $request){
        $html = view("components.button",["name"=>"Themes Page","class"=>"btn btn-primary btn-large","content"=>"The content shows it is from themes page"]);
        return response()->json(["id"=>1,"html"=>$html,"result"=>"success"]);
    }
}
