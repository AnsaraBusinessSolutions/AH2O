<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SidebarMenusList;
use App\Models\SideBarSubMenuList;

class MenuController extends Controller
{
    //

	public function __construct(){
	
	}
	public function createMenu(){
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
	public function createMenuData(Request $request){
		$rules = [
			"menu_name"=>"required|unique:sidebar_menu_listing",
			//"menu_icon"=>"required",
			//"menu_font"=>"required",
			"menu_parent"=>"required"
		];
		$validator = Validator::make($request->all(),$rules);
		if($validator->passes()){
			$menu_parent = $request->menu_parent!=0?$request->menu_parent:0;
			$menu_name = $request->menu_name!=""?$request->menu_name:"";
			$inserted = \DB::table("sidebar_menu_listing")->insert(["menu"=>$menu_name,"parent"=>$menu_parent]);
			if($inserted){
				return redirect()->route("home")->with("success","Menu created successfully");
			}
			else{
				return redirect()->route("home")->with("success","Menu created successfully");
			}
		}
		else{
			return redirect()->route("home")->with("success","Error data given is not valid");
		}
	}
}
