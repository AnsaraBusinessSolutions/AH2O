<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ComponentController;
use App\Http\Controllers\API\ComponentOutputController;

class ComponentElementController extends Controller
{
    //
    public $component_input ;
    public $component_output;
    public function __construct(){
        $this->component_input = new ComponentController();
        $this->component_output = new ComponentOutputController();
    }
    public function getComponents(Request $request){
        $rules = [
            "input_type"=>"required",
            "name"=>"required",
            "class"=>"required" ,
            'element_type'=>'required'
        ];
        $validator = \Validator::make($request->all(),$rules);
        if($validator->passes()){
            $object = "";
            $response = "";
            if($request->element_type=='output'){
                $object = $this->component_output;
                $response = $object->getOutputs($request);
            }
            else{
                $object = $this->component_input;
                $response = $object->getInputs($request);
            }
            return response()->json(["result"=>"success","html"=>$response]);
        }
        else{
            return response()->json(["result"=>"failed","html"=>"<p> No content received from the server </p>"]);
        }
    }
}
