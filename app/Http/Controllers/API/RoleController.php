<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function __construct(){

    }
    public function roleList(){
        
        $roles = \DB::table("roles")->where("id",">",0)->select("id as ID","name as Name","active as Active")->get();
        $header = [];
        $data = [];
        foreach($roles as $key=>$role){
            if(empty($header)){
                $header = array_keys((array)$role);
            }
            $data[] = (array)$role;
        }
        $html = $this->listTable($header,$data,"role");
        $html = view("user.roles",["html"=>$html])->render();
        
        return response()->json(["result"=>"success","html"=>$html]);
    }
    public function createRole(Request $request){
        $html = "";
        $html = "<div class='container'>";
        $html .= "<div class='row'>";
        $html .= "<div class='col-md-9'>";
        $html .= "<label class='form-label' for='role_name'>Name :</label>";
        $html .= "<input type='text' name='role_name' id='role_name' class='form-control' value=''>";
    //    $html .= "</div>";
    //    $html .= "<div class='col-md-9'>";
    //    $html .= "<label class='form-label' for='role_active'>Active</label>";
        $html .= $this->getSwitchHtml("role_active",'checked="checked"');
        $html .= "</div>";
        $html .= "</div>";
        $html .= "</div>";
        return response()->json(["result"=>"success","html"=>$html,"title"=>"Create Role"]);
    }
    public function storeRole(Request $request){
        $rules = [
            "name"=>"required|string"
        ];
        $validator = \Validator::make($request->all(),$rules);
        if($validator->passes()){
            $name = $request->name!=""?$request->name:"";
            $active = isset($request->active)?($request->active==true?1:0):1;
            $exists = $this->roleExists($request,1);//''
            if($exists){
                return response()->json(["result"=>"failed","message"=>"Role Already Exists"]);
            }
            else{
                $created = \DB::table("roles")->insert(["name"=>$name,"active"=>$active]);
                if($created){
                    return response()->json(["result"=>"success","message"=>"Role created Successfully"]);
                }
                else{
                    return response()->json(["result"=>"failed","message"=>"Role creation failed"]);
                }
            }
        }
        else{
            return response()->json(["result"=>"failed","message"=>"Role creation failed"]);
        }
    }
    public function roleExists(Request $request,$return=0){
        $rules = [
            "name"=>"required|string"
        ];
        $validator = \Validator::make($request->all(),$rules);
        if($validator->passes()){
            $name = $request->name?$request->name:"";
            $exists = \DB::table("roles")->where("name","=",$name)->exists();
            if($return)
            return $exists;
            else
            return response()->json(["result"=>"success","exists"=>$exists]);
        }
        else{
            if($return)
            return 0;
            else
            return response()->json(["result"=>"failed","exists"=>0]);
        }
    }
    public function updateRoleStatus(Request $request){
        $rules = [
            "id" => "required|int",
            "status"=>"required"
        ];
        $validator = \Validator::make($request->all(),$rules);
        if($validator->passes()){
            $id = $request->id?$request->id:0;
            $status = isset($request->status)?($request->status=="true"?true:false):0;
            $continue = \DB::table("roles")->where("id","=",$id)->update(["active"=>$status]);
            if($continue){
                return response()->json(["result"=>"success","message"=>"Role is now made ".($status?"Active":"InActive")]);
            }
            else{
                return response()->json(["result"=>"failed","message"=>"Role is not updated"]);
            }
        }
        else{
            
            return response()->json(["result"=>"failed","message"=>"unable to update role status"]);
        }

    }
    public function listTable($header,$data,$page=""){
        
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
            
                
                
                $html .= "<tr>";
                for($j=0;$j<count($header);$j++){
                    if($header[$j]=='Active'){
                        $html .= "<td>
                        <div class='row'>
                        <div class='col-md-2 custom-control custom-switch'> 
                        <input type='checkbox' class='custom-control-input ' id='active_role_".$data[$i]["ID"]."' ".($data[$i]["Active"]?'checked="checked"':"").">
                        <label class='custom-control-label' for='active_role_".$data[$i]["ID"]."'>Active</label>
                        </div>
                        </div>
                        </td>";
                    }
                    else{
                    $html .= "<td>".$data[$i][$header[$j]]."</td>";
                    }
                    
                //$html .= "<td>".$data[$i][$header[1]]."</td>";
                //$html .= "<td>".$data[$i][$header[2]]."</td>";i
                //$html .= "<td>".$data[$i][$header[3]]."</td>";
                }
                $html .= "</tr>";
            }
            
            $html .= "</tbody>";
        }
        $html .= "</table>";
        
        return $html;
    }
    public function getSwitchHtml($id,$checked){
        return "<div class='custom-control custom-switch'> 
                        <input type='checkbox' class='custom-control-input ' id='".$id."' ".($checked).">
                        <label class='custom-control-label' for='".$id."'>Active</label>
                        </div>";
    }
}
