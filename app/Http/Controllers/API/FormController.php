<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ComponentOutputController;
use App\Http\Controllers\API\ComponentController;
class FormController extends Controller
{
    //
    public $component_output_controller;
    public $component_input_controller;
    public $html_content = [];
    public $div_count  =   0;
    public function __construct(){
        $this->component_output_controller = new ComponentOutputController();
        $this->component_input_controller = new ComponentController();

    }
    public function listForms(){
        $formname = 'get_form_form_listing_1';
        $onclick = "onclick= 'javascript:getGenerateForm()' ";
        $html = "<div class='container'>
                    <div class='row'>
                    <div class='col-md-3'>
                        <label class='Form List' for='form_list_data'>Form List:</label><br>
                        <a href='#' class='get_form get_form_form_listing_1' ><span class='get_form get_form_form_listing_1' ".$onclick.">Form Listing 1</span></a>
                    </div>";
        
        $html .="<div class='col-md-6'>
                    <button name='create_form' id='create_form' class='btn btn-primary pull-right' onclick='create_form(this)'>Create Form</button>
                    </div>
                    </div>";
                
        $forms_in_table = \DB::table("form_template")->select("name","template_content","name_slug")->get();
        $html .= "<div class='row'>";
        $html .= "<div class='col-md-3'>";
        $html .= "<label class='form-label' >Form Created with templates List</label><br>";
        foreach($forms_in_table as $form_table){
          $function_name = 'getGenerateForm("'.$form_table->name.'")';
          $onclick = "onclick= 'javascript:".$function_name."' ";
          $html .= "<a href='#' class='get_form get_form_".$form_table->name_slug."'><span class='get_form get_form_".$form_table->name_slug."' ".$onclick.">".$form_table->name."</span></a><br>";
        }       
        $html .= "</div>";
        $html .= "<div class='col-md-3'>";
        $html .= "<label class='form-label' >Edit Form</label><br>";
        foreach($forms_in_table as $form_table){
          $function_name = 'editForm("'.$form_table->name.'")';
          $onclick = "onclick= 'javascript:".$function_name."' ";
          $html .= "<a href='#' class='get_form get_form_".$form_table->name_slug."'><span class='edit_form edit_form_".$form_table->name_slug."' ".$onclick.">Edit</span></a><br>";
        }       
        $html .= "</div>";
        $html .= "</div>";
        $html .= "</div>";    
        return response()->json(["result"=>"success","html"=>$html]);        
    }
    public function createForm(){
      $html = view("form.create")->render();
      return response()->json(["result"=>"success","html"=>$html]);  
    }
    public function updateForm(Request $request){
      $rules = [
        "formname"=>"required"
      ];
      $validator = \Validator::make($request->all(),$rules);
      if($validator->passes()){
        $form_name = $request->formname?$request->formname:"";
        $form_template  = \DB::table("form_template")->select("template_content")->where("name",$form_name)->get();
        if(count($form_template)>0){
          $template_content = $form_template[0]->template_content?$form_template[0]->template_content:"";
          $template_content = json_decode($template_content,true);
          //$this->getHtml($template_content[0]);
          //$this->getHtml($template_content);
          $html = $this->generateDynamicForm($request,1,1);//s
          //print_r($this->html_content); //'
          //echo $this->div_count;//'
        }
        $response_data = $html;
        $htmlresponse = "";
        $result = "success";
        
        if($response_data!=""){
          $html = $response_data;
          $form_name = $request->formname?$request->formname:'';
          $htmlresponse = view("form.update",["result"=>$result,"html"=>$html,"formname"=>$form_name])->render();
        }
        else{
          $result = "failed";
        }
        return response()->json(["result"=>$result,"html"=>$htmlresponse]);
      }
    }
    public function getHtml($template_content,$element="",$class="",$i=0){
      if(is_array($template_content)==false) return ;
      
      foreach($template_content as $key=>$template){
           $this->html_content[] =$key;
           $this->getHtml($template);
           if(strpos($key,"DIV")!==false){
             $this->div_count++;
           }
      }
    
    }
    public function generateForm(){
        
        $input_field = "input_".\Str::random(6);
        $html = "";
        $html .= "<form action='#' name='generateForm'>";
        $label_html = $this->component_output_controller->getLabels("input_".\Str::random(6),"",$input_field,"Email Address");
        $input_html = $this->component_input_controller->getInputsEmail($input_field,"form-control", "" , "");
        $html .= $this->wrapHtml($label_html.$input_html,"col-md-6");
        $input_field = "input_".\Str::random(6);
        $label_html = $this->component_output_controller->getLabels("input_".\Str::random(6),"",$input_field,"Comments");
        $input_html = $this->component_input_controller->getInputsTextarea($input_field,"form-control", "" , 3,"");
        $html .= $this->wrapHtml($label_html.$input_html,"col-md-6");
        $buttons_html = $this->component_input_controller->getButtons("submit_button","btn btn-primary","Submit");
        $buttons_html .= $this->component_input_controller->getButtons("cancel_button","btn btn-default","Cancel");
        $button = $this->wrapHtml($buttons_html,"row col-md-6");
        $html .= $button;
        $html .= '</form>';
        $html = $this->wrapContainer($html);
        $html = "<div class='container'><div class='row'>The Sample form generated from the available filed contents: </div><br>".$html."</div>";
      
        /*$html = '<form class="row g-3" id="generateForm" name="generateForm">
        <div class="col-md-6">
          <label for="inputEmail4" class="form-label">Email</label>
          <input type="email" class="form-control" id="inputEmail4">
        </div>
        <div class="col-md-6">
          <label for="inputPassword4" class="form-label">Password</label>
          <input type="password" class="form-control" id="inputPassword4">
        </div>
        <div class="col-12">
          <label for="inputAddress" class="form-label">Address</label>
          <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
        </div>
        <div class="col-12">
          <label for="inputAddress2" class="form-label">Address 2</label>
          <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
        </div>
        <div class="col-md-6">
          <label for="inputCity" class="form-label">City</label>
          <input type="text" class="form-control" id="inputCity">
        </div>
        <div class="col-md-4">
          <label for="inputState" class="form-label">State</label>
          <select id="inputState" class="form-select">
            <option selected>Choose...</option>
            <option>...</option>
          </select>
        </div>
        <div class="col-md-2">
          <label for="inputZip" class="form-label">Zip</label>
          <input type="text" class="form-control" id="inputZip">
        </div>
        <div class="col-12">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="gridCheck">
            <label class="form-check-label" for="gridCheck">
              Check me out
            </label>
          </div>
        </div>
        <div class="col-12">
          <button type="submit" class="btn btn-primary">Sign in</button>
        </div>
      </form>';*/

        return response()->json(["result"=>"success","html"=>$html]);
    }
    public function generateDynamicForm(Request $requesst,$return_html=0,$nowrap=0){
      $input_field  = \Str::random(6);//''
      $rules = [
        "formname"=>"required"
      ];
      
      $validator = \Validator::make($requesst->all(),$rules);
      if($validator->passes()){
        $name = $requesst->formname?$requesst->formname:"";
        if($name!=""){
        $form_template = \DB::table("form_template")->where("name",$name)->get();
            $form_template_content = isset($form_template[0]->template_content)?json_decode($form_template[0]->template_content,true):[];
            
            $html = "";
            if(count($form_template)>0){
              //print_r($form_template_content);
              
              foreach($form_template_content as $key=>$form_content){
                $html .= $this->getItems($form_content);
              }
            
            }
            else{
              
            }
            
            if($return_html==0)
            return response()->json(["result"=>"success","html"=>$this->wrapContainer($this->wrapHtml($html,"col-md-12")) ]);
            else if($nowrap==1)
            return $html;
            else
            return $this->wrapContainer($this->wrapHtml($html,"col-md-12"));
          }
        else{
          if($return_html==0)
          return response()->json(["result"=>"failed","html"=>"","message"=>"Form is not found"]);
          else
          return "";
        }
      }
      else{
        if($return_html==0)
        return response()->json(["result"=>"success","html"=>""]);
        else
        return "";
      }
    }
    public function getItems($content){
    //  if(is_array($content)){
        $html = [];
        $position_html = 0;
        foreach($content as $key=>$conte){  
          if(is_array($conte) && count($conte)>0){
            foreach($conte as $cont){
              $position_html = isset($cont["position"])?$cont["position"]:$position_html;
              if(substr($key,0,3)=="DIV" || substr($key,0,3)=="div"){
                $html[$position_html][] = "<div class='".substr($key,4)."'>";
              }
              $html[$position_html][] = $this->getElementHtml($cont);
              if(substr($key,0,3)=="DIV" || substr($key,0,3)=="div"){
                $html[$position_html][] = "</div>";
              }

            }
          }  
        }
        ksort($html);   
    return implode("",\Arr::collapse($html));
    }
    public function getElementHtml($params){
            if(isset($params["tagname"]) && $params["tagname"]!=""){
              $type =  isset($params["type"])?("type='".$params["type"]."'"):"";
              $name = isset($params["name"])?("name='".$params["name"]."'"):"";
              $class = isset($params["class"])?("class='".$params["class"]."'"):"";
              $for = isset($params["for"])?("for='".$params["for"]."'"):"";
              $onclick = isset($params["onclick"])?("onclick='".$params["onclick"]."'"):"";
              $value=(isset($params["value"]))?("value='".$params["value"]."'"):"";
              $html ="<".$params["tagname"]." ".($type!=""?$type:"")." ".($name!=""?$name:"")." ".($class!=""?$class:"")." ".($for!=""?$for:"")." ".$onclick." ".$value.">".(isset($params["content"])?$params["content"]:"");
              if(in_array($params["tagname"],["label","button","textarea"])){
                $html .= "</".$params["tagname"].">";
              }

            return $html;
            }
            else{
              return "";
            }
    }
    public function FormSave(Request $request){
      $rules = [
        "name"=>"required",
        "template_data"=>"required"
      ];
      $validator = \Validator::make($request->all(),$rules);
      if($validator->passes()){
        $name = $request->name?$request->name:"";
        $slug = \Str::slug($name);
        $template_data = $request->template_data?$request->template_data:"";
        $template_url = $request->template_url?$request->template_url:"";
        if($name!="" && $template_data!=""){
            $exists = \DB::table("form_template")->where("name_slug","=",$slug)->exists();
            if($exists){              
              \DB::table("form_template")->where("name_slug","=",$slug)->update(["template_content"=>$template_data,"template_form_url"=>$template_url]);
            }          
            else{
              \DB::table("form_template")->insert(["name_slug"=>$slug,"template_content"=>$template_data,"name"=>$name,"template_form_url"=>$template_url]);
            }
            return response()->json(["result"=>"success","message"=>"Fields given are stored successfully"]);
        }
        else{
          return response()->json(["result"=>"failed","message"=>"Fields given is empty"]);
        }
      }else{
        return response()->json(["result"=>"success","message"=>"Fields are mising"]);
      }

    }
    public function FormNameCheck(Request $request){
      $rules = [
          "name"=>"required"
      ];
      //if
      $validator = \Validator::make($request->all(),$rules);//'""
      if($validator->passes()){
        $name = $request->name?$request->name:"";
        $exists=\DB::table("form_template")->where("name","like","%".$name."%")->exists();
        if($exists){
          return response()->json(["result"=>"success","message"=>"Entry already exists"]);
        }
       /* else{
          return response()->json(["result"=>"failed","message"=>"Entry"])
        }
       */ 
      }
    }
    public function wrapHtml($html="",$class=""){
        return "
            <div class='".$class."'>
                ".$html."
            </div>
        ";
    }
    public function wrapContainer($html="",$class=""){
        return "
        <div class='container'>
        ".$html."
        </div>
        ";
    }
}
