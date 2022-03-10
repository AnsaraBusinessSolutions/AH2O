<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use JWTAuth;
use App\User;

class UserController extends Controller
{
    //
    public function __construct(){
        $this->middleware("auth-middleware");
    }
    public function listUsers(Request $request){
        $session = $this->getSession($request);
        if(empty($session)){
            return response()->json(["result"=>"failed","html"=>"<p>No Content Received</p>","session"=>"No Session data found"]);
        }
        else{
            $role = $session["id"]?$session["role"]:0;
            $users  = User::leftJoin("roles","users.role","=","roles.id")->select("users.id","roles.name as rolename","users.name","users.email")->where("roles.id",">=",$session["role"])->get();
            $usersalldata = [];
            foreach($users as $user){
                $usersalldata[] = ["id"=>$user["id"],"rolename"=>$user["rolename"],"name"=>$user["name"],"email"=>$user["email"]];
            }
            //echo count($usersalldata);
            //exit;
            $html = $this->listTable(["id","rolename","name","email"],$usersalldata);
            
            return response()->json(["result"=>"success","html"=>$this->getHeader($html),"session"=>$session,"users"=>$users]);
        }
    }
    public function createUser(Request $request){
        $html = "";
        $session = $this->getSession($request);
        if(empty($session)==false){
            $role = $session["role"]?$session["role"]:0;
            if($role){
                $html = "";
                $post_url = "'user-create-admin'";
                $html .= "<div class='container'>";
                    $html .=  "<div class='col-md-9'><form name='user-create-admin' action='#'>";
                        $html .= "<div class='row'>";
                                $html .= "<div class='col-md-3'>";
                                    $html .= "<label class='form-label'>Email Address</label>";
                                    $html .= "<input type='email' name='email' id='email' value=''>";
                                $html .= "</div>";
                                $html .= "</div>";
                                $html .= "<div class='row'>";
                                $html .= "<div class='col-md-3'>";
                                    $html .= "<label class='form-label'>Name</label>";
                                    $html .= "<input type='email' name='name' id='name' value=''>";
                                $html .= "</div>";
                                $html .= "</div>";
                                $html .= "<div class='row'>";
                                $html .= "<div class='col-md-3'>";
                                    $html .= "<label class='form-label'>Role</label>";
                                    $html .= "<select name='role' class='form-control'>";
                                    $html .= $this->getRole($session["role"]);
                                    $html .= "</select>";
                                $html .= "</div>";
                                $html .= "</div>";
                                $html .= "<div class='row'>";
                                $html .= "<div class='col-md-3'>";
                                    $html .= "<label class='form-label'>Password</label>";
                                    $html .= "<input type='password' name='password' id='password' value=''>";
                                $html .= "</div>";
                                $html .= "</div>";
                                $html .= "<div class='row'>";
                                $html .= "<div class='col-md-3'>";
                                    $html .= "<label class='form-label'>Confirm Password</label>";
                                    $html .= "<input type='password' name='confirm_password' id='confirm_password' value=''>";
                                $html .= "</div>";
                                $html .= "</div>";
                                $html .= "<div class='row'>";
                                $html .= "<div class='col-md-3'>";
                                    $html .= "<button name='submitform' id='submitform' type='button' class='btn btn-primary' onclick='postForm(\"user-create-admin\")'>Submit</button>";
                                    $html .= "<button name='cancelform' id='cancelform' type='button' class='btn btn-secondary'>Cancel</button>";
                                $html .= "</div>";
                                $html .= "</div>";
                        $html .= '</div>';
                    $html .= "</form></div>";
                $html .= "</div>";
                return response()->json(["result"=>"success","html"=>$html]);
            }
            else{
                return response()->json(["result"=>"failed","html"=>"<p> No Content Found.</p>"]);
            }
        }
        else{
            return response()->json(["result"=>"failed","html"=>"<p> No Content Found.</p>"]);
        }
    }
    public function createUserPost(Request $request){
        $rules = [
                "email"=>"required|unique:users",
                "name"=>"required|unique:users",
                "role"=>"required",
                "password"=>"required",
                "confirm_password"=>"required|same:password"
        ];
        $validator = \Validator::make($request->all(),$rules);
        if($validator->passes()){
            $email = $request->email;
            $name = $request->name;
            $role = $request->role;
            $password = $request->password;
        
            $created = User::create([
                "email"=>$email,
                "name"=>$name,
                "role"=>$role,
                "password"=>\Hash::make($password)
            ]);
            if($created){
                return response()->json(["result"=>"success","message"=>"User created Successfully."]);
            }
            else{
                return response()->json(["result"=>"failed","message"=>"User Creation Failed"]);
            }
        }
        else{
            if(empty($validator->messages()) == false){
            $message = $validator->messages()->first();
            }
            else{
                $message = "Unknown Error Detected";
            }
            return response()->json(["result"=>"failed","message"=>$message]);
        }
    }
    public function getSession(Request $request){
        $auth_token = $request->header("Authorization");
        $auth_token = "Session:".substr($auth_token,-10);
        //secho "\nUser controller ".$auth_token." ";
        $user = session()->get($auth_token);
        return $user;
        //print_r(session()->get($auth_token));
        //exit;//''
    }
    public function listTable($header,$data){
        
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
                $html .= "<td>".$data[$i][$header[0]]."</td>";
                $html .= "<td>".$data[$i][$header[1]]."</td>";
                $html .= "<td>".$data[$i][$header[2]]."</td>";
                $html .= "<td>".$data[$i][$header[3]]."</td>";
                $html .= "</tr>";
            }
            
            $html .= "</tbody>";
        }
        $html .= "</table>";
        return $html;
    }
    public function getHeader($html){
        return "
            <div class='container'>
                <div class='col-md-9'>
            ".$html."
                </div>
            </div>
        ";
    }
    public function getRole($data){
        $role = \App\Role::select("id","name")->where("id",">",$data)->get();
        if(empty($role)==false){
            $html = "";
            $html .= "<option value=''>Select Role</option>";
            foreach($role as $ro){
                $html .= "<option value='".$ro->id."'>".$ro->name."</option>";
            }    
            return $html;
        }
        else{
            $html = "<option value=''>No Roles Found</option>";
            return $html;//'
        }
    }
}
