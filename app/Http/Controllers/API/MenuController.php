<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\SidebarUserRoleMapping;

class MenuController extends Controller
{
    //
    public $mapping = [];
    public function __construct(){

    }
    public function createMenu(Request $request){
        $rules = [
			"menu"=>"required|unique:sidebar_menu_listing",
			//"menu_icon"=>"required",
			//"menu_font"=>"required",
			"menu_parent"=>"required"
		];
        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){
            $menu_parent = $request->menu_parent!=0?$request->menu_parent:0;
			$menu_name = $request->menu!=""?$request->menu:"";
			$inserted = \DB::table("sidebar_menu_listing")->insert(["menu"=>$menu_name,"parent"=>$menu_parent]);
			if($inserted){
				return response()->json(["result"=>"success","message" => "Menu created successfully"]);
			}
			else{
				return response()->json(["result"=>"success","message"=>"Menu created successfully"]);
			}
        }
        else{
            return response()->json(["result"=>"error","message"=>$validator->errors()]);
        }
    }
    public function getmenulist(){
		$menu_list = [];
		$menu_direct_list = [];
		$menu_direct_submenu = [];
		$menu_now_list=[];
		$menu_direct = \DB::table("settings")->select("setting_value","setting_name")->where("setting_name","=","sidebar")->get();
		$menu_list = [];
		$menu_content = $menu_direct[0]->setting_value;//'
		$menu_list = json_decode($menu_direct[0]->setting_value);
		foreach($menu_list as $menu){
			$menu_direct_list[]=$menu->menu_name;//'
		}
		/*foreach($menu_direct as $menu){
			$menu_direct_list[$menu->id] = $menu->menu;
			$menu_list[] = $menu; 
		}*/
		return view("menu.create",["menu_direct_list"=>$menu_direct_list,"menu_list"=>$menu_list]);
	}
    public function listMenu(Request $request){
        $role=2;
        
	$role = Auth::guard("api")->user()->role?Auth::guard("api")->user()->role:2;
	$user_menu = SidebarUserRoleMapping::where("role",$role)->select("menu_id")->get();
    if($user_menu[0]->menu_id!=""){
        $menu_list = explode(",",$user_menu[0]->menu_id);
        $this->getMenudetail($menu_list);
    }
    
    }
    public function getMenudetail($menu_list){
        $menu_detail = \DB::table("sidebar_menu_listing")->whereIn("id",$menu_list)->select("id","parent","menu")->get();
        $menu_available_detail = [];
        foreach($menu_detail as $menu){
            //echo "\n".$menu->id." ".$menu->parent." ".$menu->menu;
            $menu_available_detail[] = ["id"=>$menu->id,"parent"=>$menu->parent,"menu"=>$menu->menu];
        }
        //foreach()
        $this->appendMapping($menu_available_detail,0);
        foreach($menu_available_detail as $key=>$menu){
        $this->appendMapping($menu_available_detail,$menu["id"]);
        }
        //print_r($this->mapping);
        //$this->mapping = array_reverse($this->mapping);
        //print_r($this->mapping);
        //foreach($this->mapping as $key => $map){
        //    $elm = str_replace("parent_","",$key);
            $this->findMapping($this->mapping,0);  
            
        //}
        //echo "\n returned element";
        //print_r($this->mapping["returned_elements"]);
        //exit;
        /*echo "<pre>";
        print_r($this->mapping);
        echo "</pre>";*/
    }
    public function appendMapping($menu_available_detail,$start_position){
        foreach($menu_available_detail as $menu_detail){
            if($menu_detail["parent"]==$start_position){
                
                $menu_detail_parent = ["id"=>$menu_detail["id"],"menu"=>$menu_detail["menu"]];

                $this->mapping["parent_".$start_position][] = $menu_detail_parent;
            }
            
        }
    }
    public function findMapping($mapping,$parent_elm){
       echo "\n".$parent_elm." ";
       if(is_array($mapping["parent_".$parent_elm])){
                    foreach($mapping["parent_".$parent_elm] as $map_elm)
                    $this->mapping["returned_element"] = $this->findMapping($mapping,$map_elm[]);
       }
    }
    public function menu_save(Request $request){
        $rules = [
            "settingname"=>"required",
            "settingvalue"=>"required"
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->passes()){
            $exists = 0;
            $exists = \DB::table("settings")->where("setting_name",$request->settingname)->exists();
            $inserted = 0;
            if($exists){
                $inserted = \DB::table("settings")->where("setting_name",$request->settingname)->update(["setting_value"=>$request->settingvalue]);
            }
            else{
                $inserted = \DB::table("settings")->insert(["setting_name"=>$request->settingname,"setting_value"=>$request->settingvalue]);
            }
            if($inserted){
                return response()->json(["id"=>1,"message"=>"Successfully Updated"]);
            }
            else{
                return response()->json(["id"=>2,"message"=>"Unable to update"]);
            }
        }
    }
}
