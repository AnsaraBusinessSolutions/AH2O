<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class ComponentController extends Controller
{
    //
    public $input;
    public function __construct(){
    //    $this->middleware("");
    }
    public function getInputs(Request $request ){
        $rules = [
            "input_type"=>"required",
            "name"=>"required",
            "class"=>"required",
            
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){
            $input = $request->input_type?$request->input_type:"";
            $name = $request->name?$request->name:"";
            $class = $request->class?$request->class:"";
            $content = $request->content?$request->content:"";
            $rows = $request->rows?$request->rows:7;
            $column_size = $request->column_size?$request->column_size:"";
            $html = "";
            if($input){
                    //$this->input = "get".ucfirst($input)."('".$name."','".$class."','')";
                    
                    //$html = $this->input;
                    switch($input){
                        case 'buttons':
                            $html = $this->getButtons($name,$class,$content);
                            break;
                        case 'inputs':
                            $html = $this->getInputsText($name,"form-control",$content);
                            break;
                        case 'dropdowns':
                            $html = $this->getDropDowns($name,$class,$content);
                            break;//'    
                        case 'icons':
                            $html = $this->getIcons($name,$class,$content);
                            break;
                        case 'badges':
                            $html = $this->getBadges($name,$class,$content);
                            break;  
                        case 'cards':
                            $html = $this->getCards($request);
                            break;      
                        case 'list-groups':
                            $html = $this->getListGroups($request);
                            break;
                        case 'navigation-menus':
                            $html = $this->getNavigationMenus($request);
                            break;
                        case 'check-boxes':
                            $html = $this->getCheckBoxes($request);
                            break;
                        case 'textarea':
                            $html = $this->getInputsTextarea($name,$class,"",$rows,$content);
                            break;
                        case 'radio-buttons':
                            $html = $this->getRadioBoxes($name,$class,$content,"");
                            break;
                        case 'inputs-email':
                            $html = $this->getInputsEmail($name,$class,"");
                            break;    
                        case 'inputs-password':
                            $html = $this->getInputsPassword($name,$class);
                            break;    
                        case 'inputs-phone':
                            $html = $this->getInputsPhone($name,$class,"");
                            break;
                        case 'inputs-select':
                            $html = $this->getInputsSelect($name,$class,$val);
                            break;
                            default:
                            $html = "<p>There is no available fields or type of input you mentioned.</p>";
                            break;        
                    }
                }
                //'
                //$html = "<div class='container'><p> Following are the contents that you are requested for, </p><br>".$html."</div>";
            return $this->wrapHtml($html);
                return response()->json(["id"=>0,"result"=>"success","html"=>$html]);
        } 
        else{
            return $html;
            return response()->json(["id"=>1,"result"=>"failed","html"=>$html]);
        }
    }
    public function wrapHtml($html, $class="col-md-3"){
        return "<div class='".$class." added_item_div'>
                ".$html."
                </div>";
    }
    public function getButtons($name="Button",$class="btn btn-primary",$content="Button"){

        //$button= ["Primary","Secondary","Success","Info","Warning","Danger","Focus","Alt","Light","Dark","Link"];
        //$buttons = \DB::table("components")->leftJoin("components_type","components.component_type_id","=","components_type.id")->where("components_type.name","=","buttons")->select("components.component_class","components.component_style")->get();
        $html = "";
        //$html = "<div class='container'>";

        /*$html .= "<div class='row'>";
        $html .= "<p>The button types are generated under the same template,</p>";
        $html .= "</div>";
        */
        //$html .= "<div class='row'>";
            //$html .= "<div class='col-md-2'>";
            $html .= "<button name='".$name."' class='".$class." added_item' onclick='javascript:return false;'>".$content."</button>";
            //$html .= "</div>";
        //$html .= '</div>';
        //$html .= "</div>";
        return $html;
        //return response()->json(["result"=>"success","message"=>"Content generated successfully.","html"=>$html]);
    }
    public function getButtonWithBadges($name="Button",$class="btn btn-primary",$content=""){
        $badge = $this->getBadges($name,$class,$content);
        if($badge!=""){
            $html = "";
            $html .= $this->getButtons($name,$class,$content.$badge);
            return $html;
        }
        return "";
    }
    public function getDropDowns($name="",$class="",$content=""){
        $html_dropdown = '<div class="dropdown d-inline-block">
        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="mb-2 mr-2 dropdown-toggle btn btn-primary">Primary</button>
        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start" style="position: absolute;">
            <button type="button" tabindex="0" class="dropdown-item">Menus</button>
            <button type="button" tabindex="0" class="dropdown-item">Settings</button>
            <h6 tabindex="-1" class="dropdown-header">Header</h6>
            <button type="button" tabindex="0" class="dropdown-item">Actions</button>
            <div tabindex="-1" class="dropdown-divider"></div>
            <button type="button" tabindex="0" class="dropdown-item">Dividers</button>
        </div>
    </div>';
    return $html_dropdown;
        return response()->json(["result"=>"success","message"=>"Content Generated Successfully","html"=>$html_dropdown]);
    }
    public function getIcons(){
        $html = "<div class='container'>";
        $html .= "<i class='pe-7s-arc icon-gradient bg-premium-dark added_item'></i>";
        $html .= "</div>";
        return $html;
    }
    public function getInputsText($name="",$class="",$value="",$content=""){
    //    $html .= '<input type="text" id="nameInput"  onkeypress="return ((event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32))">';
    //$html .= "<p> The normal html text with number input field</p>";
    //$html .= view('components.input-html',["name"=>"text","class"=>"form-control","type"=>"text","id"=>"text_name_only_text_content","keypress"=>"return 1"])->render();
    $html = "<input type='text' name='".$name."' class='".$class."' value='".$value."'>";
    return  $html;//'
        //return response()->json(["result"=>"success","html"=>$html]);

    }   
    public function getInputsEmail($name="",$class="",$value="",$content=""){
        $html = "<input type='email' name='".$name."' class='".$class." added_item' value='".$value."'>";
        return $html;
    }
    public function getInputsPhone($name="",$class="",$value="",$content=""){
        $html = "<input type='tel' name='".$name."' class='".$class." added_item' value='".$value."'>";
        return $html;
    }
    public function getInputsTextarea($name="",$class="",$value,$rows,$content=""){
        $html = "<textarea name='".$name."' class='form-control ".$class." added_item' rows='".$rows."' value='".$value."'></textarea>";
        return $html;
    }
    public function getInputsPassword($name="",$class=""){
        $html = "<input type='password' name='".$name."' class='".$class."' value=''>";
        return $html;
    }
    public function getInputsSelect($name="",$class="",$value=[],$multiple=""){
        $html = "<select name='".$name."' class='".$class."'>";
        if(is_array($value)){
            foreach($value as $key=>$val){
                $html .= "<option value='".$key."'>".$val."</option>";
            }
        }
        $html .= "</select>";
        return $html;//'
    }
    public function getCheckBoxes(Request $request){
        $name = $request->name?\Str::slug($request->name):"sample_checkbox_".\Str::random(6);
        $html = '<div class="form-check">';
        $html .= '<input class="form-check-input added_item" type="checkbox" value="" id="'.$name.'">';
        $html .= '<label class="form-check-label added_item" for="'.$name.'">'.$name.'</label>';
        $html .= '</div>';//"
        return $html;
    }
    public function getRadioBoxes(Request $request){
        $name = $request->name?\Str::slug($request->name):"sample_radiobox".\Str::random(6);
        $id = \Str::random(6);
        $items = $request->items?$request->items:[];
        $html = "";
        if(is_array($items) && count($items)){
            foreach($items as $item){
            $html_value = \Str::slug($item);
            $html .= "<div class='form-check'>";
            $html .= "<input type='radio' class='form-check-input added_item' name='".$name."' id='".$id."' value='".$html_value."'>";
            $html .= "<label class='form-check-label added_item' for='".$id."'>".$item."</label>";
            $html .= "</div>";
            }
        }
        else{
            $html_option = $request->value?$request->value:\Str::random(6);
            $html .= "<div class='form-check'>";
            $html .= "<input type='radio' class='form-check-input added_item' name='".$name."' id='".$id."' value='".$html_option."'>";
            $html .= "<label class='form-check-label added_item' for='".$id."'>".$html_option."</label>";
            $html .= "</div>";
        }
        return $html;
    }
    public function getBadges($name="",$class="",$content=""){
        //$html = "<div class='container'>";
        $append_html ="<span class='badge badge-light added_item'>".mt_rand(2,2)."</span>";
        //$html .= view("components.button",["name"=>"Button with Badge","class"=>"btn btn-primary","content"=>$append_html]);
        //$html .= 
        //"</div>";
        return $append_html;
        return response()->json(["result"=>"success","html"=>$append_html]);
    } 
    public function getCards(Request $request){
        $data = $request->image?$request->image:"";
        $data_header_title = $request->title?$request->title:"Card Title";
        $data_card_body = $request->body?$request->body:"Card Body";
        $data_link_redirect = $request->link?$request->link:"Card Link";
        $html = "<div class='container'>";
        $html .= '<div class="card" style="width: 18rem;">';
        if(strlen($data))   
        $html .= '<img src="..." class="card-img-top" alt="...">';

        $html .= '<div class="card-body">';
        if(strlen($data_header_title))
        $html .= '<h5 class="card-title">'.$data_header_title.'</h5>';

                if(strlen($data_card_body))
                $html .= '<p class="card-text">'.$data_card_body.'</p>';
        
                if(strlen($data_link_redirect))
                $html .= '<a href="{{ $data_link_redirect }}" class="btn btn-primary">Link</a>';

        if(strlen($data_header_title))        
        $html .= '</div>';

        $html .= '</div>';
        $html .= "</div>";

        return $html;
    }
    public function getListGroups(Request $request){
        $data = $request->items?$request->items:["item 1","item 2","item 3","item 4","item 5","item 6"];
        $html = "<ul class='list-group list-group-flush added_item'>";
        foreach($data as $listitem){
                $html .= "<li class='list-group-item'>".$listitem."</li>";
        }
        $html .= "</ul>";
        return $html;
    }
    public function getNavigationMenus(){
        $data = $request->items?$request->items:["item 1","item 2","item 3","item 4","item 5","item 6"];
        $html = '<ul class="nav added_item">';
        foreach($data as $listitem){
        $html .= '<li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">'.$listitem.'</a>
        </li>';
        }
      $html .= '</ul>';
        return $html;
    }
    public function getUtilities(){
        
    }
}
