<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use PDO;
use Validator;
use JWTAuth;
use App\Models\AuthenticationMethod;
use Auth;
use App\User;//'
use Mail;

class AuthenticationController extends Controller
{
    //
    public function __construct()
    {
        
    }
    public function loginPage(Request $request,$loginrole=""){
        $rules =[
            
        ];
        $data = $request->all();
        $html = "";
        $action = "";
        if(isset($data["action"]) && $data["action"]!=""){
                $action = $data["action"];
        }
        if($action!=""){
        
            $data_html = \DB::table("form_template")->where("name_slug","=","login-app-form")->select("template_content","template_form_url")->get();
            if(in_array($action,["admin","user"])){
                    $request_create = new \Illuminate\Http\Request;
                    $request_create->setMethod("POST");
                    $request_create->request->add(["formname"=>"Login App Form"]);
                    $html = (new \App\Http\Controllers\API\FormController())->generateDynamicForm($request_create,1,1); 
                    //';
                    return view("form_template.form_template_content",["html"=>$html,"template_form_url"=>$data_html[0]["template_form_url"]]);
                }
        }
        else{
            
            $data = $loginrole!=""?base64_decode($loginrole):"";
            if($data!=""){
              $email = "";
              $data_to_send = explode("-",$data);
              if(isset($data_to_send[2])){
              $validator = \Validator::make(["email"=>$data_to_send[2]],["email"=>"required|email"]);
              
              if($validator->passes())
              return view("auth.login",["email"=>$data_to_send[2]]);
              else
              return view("auth.login");
              }
              else{
                  return view("auth.login");
              }
            }
            else{
                return view("auth.login");
            }
        }
        //'
    }
    public function login(Request $request,$loginrole=""){
        $rules = [
            "email"=>"required|email",
            "password"=>"required"
        ];
        $validate = Validator::make($request->all(),$rules);
        if($validate->passes()){
            $loginData = [
                "email"=>$request->email,
                "password"=>$request->password
            ];
            $response = "";
            $AuthenticationMethod = AuthenticationMethod::where("active",1)->select("method")->orderBy("id","asc")->get();
            if($AuthenticationMethod){
                $authenentication = $AuthenticationMethod[0];
                if($authenentication->method ==  "passport"){
                    $response = $this->loginPassport($loginData);
                }
                if($authenentication->method ==  "jwt"){
                    $response = $this->loginJWT($loginData); 
                }
                if($authenentication->method == "default_auth"){
                    if(Auth::attempt($loginData)){
                    $response = ["id"=>1,"user"=>auth()->user(),"access_token"=>"","location"=>".".RouteServiceProvider::HOME];
                    }
                    else{
                        $response = ["id"=>2,"user"=>[],"access_token"=>"","location"=>"","message"=>"Authentication failed"];
                    }

                }
                
            }
            else{
                if(Auth::attempt($loginData)){
                    $response = ["id"=>1,"user"=>auth()->user(),"access_token"=>"","location"=>".".RouteServiceProvider::HOME];
                    }
                    else{
                        $response = ["id"=>2,"user"=>[],"access_token"=>"","location"=>"","message"=>"Authentication failed"];
                    }
            }
            return response()->json($response,200);
        }
        else{
            return response()->json(["id"=>2,"error"=>"Invalid Credentials found"],400);
        }
    }
    public function loginPassport($loginData,$onlydata=0){
        
        if (!$token = auth()->attempt($loginData)) {
            return response(['id'=>2,'message' => 'Invalid Credentials'],400);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
            Auth::login(Auth::user());
            $mail_sent = 0;
            if(Auth::user()->role==1){
            $mail_sent = $this->verificationEmail(Auth::user()->email);
            }
            $location = ".".RouteServiceProvider::HOME;
            if($mail_sent){
                $location = "/verify-pin";
            }
        if($onlydata==0)    
        return response(['id'=>'1','user' => auth()->user(), 'access_token' => $accessToken, 'location'=>$location],200);
        else
        return ['id'=>'1','user' => auth()->user(), 'access_token' => $accessToken, 'location'=>$location];
        
    }
    public function loginJWT($loginData,$onlydata=0){
        /*$rules = [
            "email"=>"required|email",
            "password"=>"required"
        ];
        $validate = Validator::make($request->all(),$rules);
        if($validate->passes()){
            $array = [
                "email"=>$request->email,
                "password"=>$request->password
            ];*/
            if(!$token = Auth::guard('api')->attempt($loginData)){
                return response()->json(["id"=>2,"error"=>"User credentials are invalid"],400);
            }
            
            Auth::login(Auth::guard("api")->user());
            $mail_sent = 0;
            if(Auth::user()->role==1){
            $mail_sent = $this->verificationEmail(Auth::guard("api")->user()->email);
            }
            $location = ".".RouteServiceProvider::HOME;
            if($mail_sent){
                $location = "/verify-pin";
            }
            if($onlydata==0)
            return response()->json(["id"=>1,"token"=> $this->createNewToken($token),"message"=>"Login Successfull","location"=>$location],200);
            else
            return ["id"=>1,"token"=> $this->createNewToken($token),"message"=>"Login Successfull","location"=>$location];
            /*}
        else{
            return response()->json(["id"=>2,"error"=>"Improper credential found"],400);
        }*/
    }
    public function verificationEmail($email_id){
        $pin = mt_rand(100000,999999);
        $data = ["name"=>"Bannaarisamy Shanmugham","pin"=>$pin];
        User::where("email","=",$email_id)->update(["multi_auth"=>$pin]);
        $mail = Mail::send(['text'=>'mail'], $data, function($message) {
            $message->to('bannari30@gmail.com', 'SuperAdmin Verification')->subject
               ('App Multiple Level Authentication');
            $message->from('verifyadmin@app.com','Verifier');
         });
         return 1;
    }
    public function verifyUser(Request $request){
        $rules = [
            "email"=>"required"
        ];
        $validator = \Validator::make($request->all(),$rules);
        if($validator->passes()){
            $email = $request->email?$request->email:"";
            $exists = 0;
            if($email!=""){
                /*echo $email;
                exit;*///''
                
                
                $exists = User::where("email","=",$email)->select("role")->get();
                
            if(isset($exists[0]) && $exists[0]->role!=""){
                $key = date("Ymd")."-".$exists[0]->role."-".$email;
                
                //return redirect()->to( "/login-page/".$key);
                return response()->json(["result"=>"success","message"=>"User account exists","redirect_url"=>"/login-page/".base64_encode($key) ]);
            }
            else{
                //return redirect()->to( "/login-page/")->with("message","user account not exists");
                return response()->json(["result"=>"failed","message"=>"User account not exists"]);
            }
            }
            else{
                return response()->json(["result"=>"failed","message"=>"User account not exists"]);
            }
        }
        else{
            //return redirect()->to( "/login-page/")->with("message","user account not exists");
            return response()->json(["result"=>"failed","message"=>"User account not exists"]);
        }
    }
    public function getUserDetail(Request $request){
        $method = $this->getAuthenticationMethod();
        $user = [];
        if($method!=""){
            
            if($method=="jwt"){
                $user= $this->getUserDetailJWT($request);
            }
            else if($method=="passport"){
                $user = $this->getUserDetailPassport($request);
            }
            else{
                $user = User::find(auth()->user()->id);
            }
            return response()->json(["id"=>1, "user"=>$user,"message"=>"Successfull"]);
        }
        else{
            $user = User::find(Auth::user()->id);
            return response()->json(["id"=>2,"user"=>$user,"message"=>"No user found"]);
        }
    }
    public function getUserDetailJWT(Request $request){
        /*$requesthandle_  = ["token"=>"required"];
        $validator = Validator::make($request->all(),$requesthandle_);
        
        if($validator->passes()){
            $usser = JWTAuth::authenticate($request->token);
            return response()->json(["user"=>$usser,"message"=>"User found"],200);
        }
        return response()->json(["user"=>[],"message"=>"User not found"],404);
        */
        try {
           
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }
    public function createNewToken($token){
        return response()->json(["access_token"=>$token, "token_type"=>"Bearer","expires_in"=>auth()->guard("api")->factory()->getTTL() * 60 ]);
    }
    public function getUserDetailPassport(Request $request){
        return response()->json(["user"=>auth("api-passport")->user(), "message"=>"successfull"]);
    }
    public function getAuthenticationMethod(){
        $authenenticationmethod = AuthenticationMethod::where("active",1)->select("method")->orderBy("id","asc")->get();
        
        if(isset($authenenticationmethod[0])){
            $authentication_method = $authenenticationmethod[0]->method;
            return $authentication_method;
        }
        else
            return "default_auth";
    }
    public function verifymailview($loginuser){
        $login = base64_decode($loginuser);
        $login_data = explode("-",$login);
        return view("verifyemail",["email"=>$login_data[2]]);
    }
    public function verifyemailpin(Request $request){
        $rules = [
            "verifypin"=>"required|min:6|max:6"
        ];
        $validator = \Validator::make($request->all(),$rules);
        if($validator->passes()){
            $email = $request->email!=""?$request->email:"";
            $pin = $request->verifypin?$request->verifypin:"";
            $exists = User::where("email","=",$email)->where("multi_auth","=",$pin)->exists();
            if($exists){
                return response()->json(["result"=>"success","location"=>"/home"]);
            }
            else{
                return response()->json(["result"=>"failed","location"=>"/verify-email"]);
            }
        }
        else{
            return response()->json(["result"=>"failed","location"=>"/verify-email"]);
        }
    }
    // public function test(Request $request){
    //     $home_controller = new \App\Http\Controllers\HomeController();
        
    // }
    // public function test1(){

    // }
}
