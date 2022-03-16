<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pages;
use App\Models\FormTemplate;
use App\Http\Controllers\API\FormController;

class PageController extends Controller
{
    //
    public function __construct(){
        $this->middleware("auth-middleware");
    }
    public function createPage(Request $request){
        $form_template = $this->getFormTemplates();
        $html =  view("pages.create",["template"=>$form_template])->render();
        return response()->json(["result"=>"success","html"=>$html]);
    }
    public function createNewPage(Request $request){
        $rules = [
                "title"=>"required|max:200",
                
        ];//''
        $validator = $this->dynamicValidation($request,$rules);
        if($validator->passes()){
            $content = $request->content;
            $pagetitle = $request->title;
            $pagename = \Str::slug($pagetitle);
            $form_linked = $request->form_linked?$request->form_linked:0;
            $content_stored = 0;
            if($form_linked){
                $content_stored = Pages::create([
                    "pagename"=>$pagename,
                    "pagetitle"=>$pagetitle,
                    "form_template_linked"=>$content,
                    "form_template_content"=>NULL
                ]);
            }
            else{
                $content_stored = Pages::create([
                    "pagename"=>$pagename,
                    "pagetitle"=>$pagetitle,
                    "form_template_linked"=>0,
                    "form_template_content"=>$content
                ]);
            }
            if($content_stored){
                return response()->json(["result"=>"success","message"=>"Page created Successfully"]);
            }
            else{
                return response()->json(["result"=>"failed","message"=>"Page creation failed"]);//''
            }
        }
        else{
            $validation_message = $validator->messages();
            
            $message = $validation_message->first()!=""?$validation_message->first():"";
            return response()->json(["result"=>"failed","message"=>isset($message)?$message:"Error While storing the page in database" ]);
        }
    }
    public function getFormTemplates(){
        $templates = FormTemplate::where("id",">",0)->select("id","name","name_slug")->get();
        $template_list = [];
        foreach($templates as $template){
            $template_list[$template->id] = $template->name;
        }
        return $template_list;
    }
    public function listPages(){
        $pages = Pages::leftJoin("form_template","pages.form_template_linked","=","form_template.id")->where("pages.id",">",0)->select("pages.id as ID","pages.pagetitle as PageTitle","form_template.name as FormTemplate")->get();
        $header = ["ID","PageTitle","FormTemplate","Action"];
        $data = [];

        foreach($pages as $page){
            //if(empty($header)){
            //    $header = array_keys((array)$page);
            //}
            $data[] = $page;
            //print_r($page);
        }
        //print_r($header);
        //exit;
        $html = $this->getTableHtml($header,$data,"");
        return response()->json(["result"=>"success","html"=>$this->wrapListTitle().$this->wrapDiv($html)]); 
    }
    public function editPage(Request $request){
        $rules = [
            "id"=>"required"
        ];
        $validator = \Validator::make($request->all(),$rules);
        if($validator->passes()){

        }
    }
    public function getTableHtml($header,$data,$page=""){
        $html = "<table class='mb-0 table table-bordered'>";
        
        if(isset($header) && count($header)){
            $html .= "<thead>";
            $html .= "<tr>";
           // $html .= "<th>S.No</th>";
            for($i=0;$i<count($header);$i++){
                
                $html .= "<th>".$header[$i]."</th>";   
            }
            $html .= "</tr>";
            $html .= "</thead>";
        }
        if(isset($data) && count($data)){
            
            $html .= "<tbody>";
            
            


            for($i=0;$i<count($data);$i++){
                //echo $header[$i];
                
                //print_r($data[$i]);
                $html .= "<tr>";
                for($j=0;$j<count($header);$j++){
                    if($header[$j]=="Action"){
                        $html .= "<td><a href='#' class='btn btn-primary'><i class='pe-7s-edit'></i></a>&nbsp;</td>";
                    }
                    else{
                    $html .= "<td>".$data[$i][$header[$j]]."</td>";
                    }
                }
                $html .= "</tr>";
            }
            
            $html .= "</tbody>";
        }
        $html .= "</table>";
        return $html;
    }
    public function dynamicValidation(Request $request,$rules){
        $validator = \Validator::make($request->all(),$rules);
        return $validator;
    }
    public function displayPage(Request $request){
        $rules = [
            "page"=>"required"
        ];
        $validator = $this->dynamicValidation($request,$rules);
        if($validator->passes()){
            $pagename = $request->page?$request->page:"";

            $page = Pages::leftJoin("form_template","pages.form_template_linked","=","form_template.id")->where("pagename",'=',$pagename)->select("pages.pagetitle","form_template.name","form_template.template_content")->get();
            $request_job = new \Illuminate\Http\Request;
            $request_job->setMethod('POST');
            $request_job->request->add(['formname' => $page[0]->name]);
            $template_html = (new FormController())->generateDynamicForm($request_job,1,1);
            if($template_html!=""){
            $template_html = $this->wrapListTitle($page[0]->pagetitle)." ".$this->wrapDiv($template_html);
            }
            else{
                $template_html = $this->wrapListTitle($page[0]->pagetitle)." <div class='row'> <div class='offset-md-1 col-md-9'> No content received for this form. </div> </div>";
            }
            return response()->json(["result"=>"success","html"=>$template_html]);
        }
        else{
            return response()->json(["result"=>"failed","html"=>"","message"=>"Unable to display Page"]);
        }
    }
    public function wrapDiv($html){
        return "
            <div class='row'>
            <div class='offset-md-1 col-md-9'>
                    ".$html."
            </div>
            </div>
        ";
    }
    public function wrapListTitle($title="Pages List"){
        return "<div class='row'>
                        <div class='offset-md-1 col-md-9'>
                                <h4>".$title."</h4>
                        </div>
                </div>
        ";
    }
}
