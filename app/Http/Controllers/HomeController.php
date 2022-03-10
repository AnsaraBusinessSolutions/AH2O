<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;//i
use Auth;
use App\Models\SidebarMenusList;
use App\Models\SideBarSubMenuList;
use App\Models\AuthenticationMethod;
use App\User;
use JWTAuth;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
	//    $this->middleware('auth-middleware');

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request,$reload=0)
    {
	//$user_id = Auth::user()->id;    
    $user_id = $request->user_id?$request->user_id:0;
	if(session()->get("reload")){
		session()->forget("reload");
	
	}
	else{
		session()->put("reload",1);
		//return view("home",["reload"=>1]);
	}
	return view('home');
    }
	public function init(Request $request){
		return view("auth.init");
	}
    public function home_page_content(Request $request){
	    $user_id = $request->user_id?$request->user_id:0;
		if($user_id==0){
			$user_id = $this->getTokenData($request);
		}
		
		$roleone = Auth::user()->role?Auth::user()->role:"";
		if($roleone!=""){
			$role = \DB::table("users")->where("id",$user_id)->select("role")->get();
			if($role[0]){
				$roleone = $role[0]->role;
			}
		}
	    $role = \App\Role::where("id",$roleone)->select("name")->first();
	    if(isset($role)){
	    	//
		    //
		    //$roles = [1=>"Admin",2=>"Manager",3=>"User"];
			$role_Data = $role->name;
			
		    $permissions = $this->getPermissionsDetail($role_Data);
		    $sidebar = $this->getSidebar($role_Data,1);
		    if(Auth::user()->role>1){
		    	$sidebar = ["sidebar"=>$sidebar,"update_sidebar"=>1];
		    }
		    else{
		    	$sidebar = ["sidebar"=>$sidebar,"update_sidebar"=>1]; 
		    }
			$topbar = $this->getUpdatedTopbar(Auth::user());
		    return response()->json(["id"=>1,"message"=>" You are a ".$role_Data.".","permissions"=>$permissions,"sidebar"=>$sidebar,"topbar"=>$topbar,"session_content"=>json_encode(session()->all()),"session_verify"=>session()->get("admin_1") ]);	    	
	    		//exit;
	    }
	    else{
				return response()->json(["id"=>1,"message"=>"There us no user related detail","permissions"=>[]]);
			exit;
	    }
    }
    public function getPermissionsDetail($name=""){
	    if($name!=""){
		    $permission_list = [
			    "Superadmin"=>["create_and_update_user" => true],
			    "admin"=>["create_and_update_user"=>false],
			    "user"=>["create_and_update_user"=>false]
		    ];

		    $role_name = $name;
		    return $permission_list[$role_name];
	    }
	    else{
	    	    return [];
	    }
    
    }
    public function getSidebar($name="",$val=0){
   	if($name!=""){
		$data = [];
		$sidemenus = \DB::table("settings")->where("setting_name","=","sidebar")->select("setting_value")->get();//SidebarMenusList::select("menu","sidebar_menu_list.position","is_this_submenu","submenu","sidebar_submenu_list.menu_id")->leftJoin("sidebar_userroles_mapping","sidebar_menu_list.id","=","sidebar_userroles_mapping.menu_id")->leftJoin("sidebar_submenu_list","sidebar_menu_list.id","=","sidebar_submenu_list.menu_id")->where("sidebar_userroles_mapping.role","=",Auth::user()->role)->get();
		$html = "";
		$setting_value = "";
		if(isset($sidemenus[0]) && $sidemenus[0]->setting_value!=""){
			$setting_value = json_decode($sidemenus[0]->setting_value);
		}
		
		//print_r($setting_value);
		//exit;
		
		//$sidesubmenus = SideBarSubMenuList::select("menu_id","submenu","position")->
		if($val){
			//print_r($data);
			//exit;
		}
		$html = view("components.sidebar",["sideMenu"=>$setting_value])->render();	
		return $html;
	} 
	else{
		return [];
	}
    }
	public function getUpdatedTopbar($updated_user){
		$data =  ""    ;
		$html = '<a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre="">
			'.Auth::user()->name.'
	</a>';
		$html .='
	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
		<a class="dropdown-item" href="'.route('logout').'" onclick="event.preventDefault();
						 document.getElementById(\'logout-form\').submit();">
			Logout
		</a>';
		$html .='
		<form id="logout-form" action="'.route('logout').'" method="POST" class="d-none">
			<input type="hidden" name="_token" value="'.csrf_token().'">                                    </form>
	</div>';
			return $html;
	}
	public function getTokenData(Request $request){
		$authentication_method = AuthenticationMethod::where("active",1)->select("method")->orderBy("id","asc")->get();
		$auth_method = "";
		if(isset($authentication_method[0])){
			$auth_method  = $authentication_method[0]["method"];
		}
		$usere_data = 0;
		if($auth_method=="passport"){
		$access_token = $request->header("Authorization");
		if(empty(session()->has("Session:".substr($access_token,-10)))==false){
			$user_data_from_session = session()->get("Session:".substr($access_token,-10));
			Auth::login(session()->get("Session:".substr($access_token,-10)));
			if(isset($user_data_from_session["id"])){
				$usere_data = $user_data_from_session["id"];
			}
		}
		else{
				// break up the token into its three respective parts
				$token_parts = explode('.', $access_token);
				$token_header = $token_parts[1];
				if(isset($token_header)){
					// base64 decode to get a json string
					$token_header_json = base64_decode($token_header);
					$token_data = json_decode($token_header_json,true);
				}
				else{
					$token_header = $token_parts[0];
					$token_header_json = base64_decode($token_header);
					$token_data = json_decode($token_header_json,true);
				}
				$user_id = \DB::table("oauth_access_tokens")->where("id",$token_data["jti"])->get();
				$usere_data = "";
				if(count($user_id)>0){
			   		$usere_data = $user_id[0]->user_id; 
				}
		Auth::login(User::find($usere_data));
			}
		}

		if($auth_method=="jwt"){
			$token = $request->header("Authorization");
			if(session()->has("Session:".substr($token,-10))){
				$user_data = session()->get("Session:".substr($token,-10));
				Auth::login($user_data);
				
				if(isset($user_data["id"]))
				$usere_data = $user_data["id"];
				else
				$usere_data = 0;
			}
			else{
			$usere_data = 0;
			if(!JWTAuth::parseToken()->authenticate())
				    $authorized = 0;
			    else
				    $authorized = 1;
		
			$users_data = "";
			if($authorized){				
				$users_data = JWTAuth::toUser($token);
			}		
			if(isset($users_data) && $users_data->id){
				$usere_data = $users_data->id;
			}
				Auth::login(User::find($usere_data));
			}
		}
		return $usere_data;
	}

}
