<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ComponentOutputController extends Controller
{
    //
    public function __construct(){

    }
    public function getOutputs(Request $request){
        $output = $request->input_type?$request->input_type:"";
        $name = $request->name?$request->name:"";
        $class= $request->class?$request->class:"";
        $for=$request->for?$request->for:"";
        $content = $request->content?$request->content:"";
        $html = "";
        switch ($output){
            case 'label':
                $html = $this->getLabels($name,$class,$for,$content);
                break;
            }
            return $this->wrapHtml($html);
        return response()->json(["id"=>1,"html"=>$html,"result"=>"success"]);    
    }
     public function wrapHtml($html,$class="col-md-3"){
         return "<div class='".$class." added_item_div'>".$html."</div>";
     }
    public function getLabels($name,$class,$for,$content){
        $html = "";
        $html = "<label class='".$class." added_item' name='".$name."' for='".$for."'>".$content."</label>";
        return $html;
    }
    public function getModals($name,$class,$content){

    }
}
