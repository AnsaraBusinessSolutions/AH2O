<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::prefix("jwt")->group(function(){
Route::post("login","API\AuthenticationController@login");
Route::get("getuserdetail","API\AuthenticationController@getUserDetail");
});
Route::prefix("pass")->group(function(){
	Route::post("/login","PassportAuthControl@login");
	Route::post("/register","PassportAuthControl@register");
	Route::get("/user","PassportAuthControl@user");
});
*/
Route::get("login","API\AuthenticationController@loginPage");
Route::post("login","API\AuthenticationController@login");
Route::get("getUserDetail","API\AuthenticationController@getUserDetail");
Route::get("home","HomeController@index");
Route::post("createmenu","API\MenuController@createMenu");//''
Route::get("listmenu","API\MenuController@listMenu");
Route::post("menu_save","API\MenuController@menu_save");
Route::get("themes","API\ThemesController@index")->name("themes");
Route::get("settings","API\SettingsController@index")->name("settings");
Route::get("get-component-menu-buttons","API\ComponentController@getButtons")->name("buttons");//s
Route::get("get-component-menu-dropdowns","API\ComponentController@getDropDowns")->name("dropdowns");//s
//Route::post("passportlogin")
Route::get("get-component-menu-inputs","API\ComponentController@getInputs")->name("inputs");
Route::get("get-component-menu-icons","API\ComponentController@getIcons")->name('icons');
Route::get("get-component-menu-badges","API\ComponentController@getBadges")->name('badges');
Route::get("get-component-menu-cards","API\ComponentController@getCards")->name('cards');
Route::get("get-component-menu-list-groups","API\ComponentController@getListGroups")->name('listgroups');
Route::get("get-component-navigation-menus","API\ComponentController@getNavigationMenus")->name('navigationmenus');
Route::get("get-component-menu-utilities","API\ComponentController@getUtilities")->name("utilities") ;
Route::get("get-element-from-list","API\ComponentElementController@getComponents")->name("getelementfromlist");
Route::get("get-inputs","API\ComponentController@getInputs")->name("inputs");
Route::get("get-forms-list","API\FormController@listForms")->name("forms");
Route::get("get-generate-form","API\FormController@generateForm")->name("generateForm");
Route::get("get-generate-dynamic-form","API\FormController@generateDynamicForm")->name("generateDynamicForm");
Route::get("edit-form","API\FormController@updateForm")->name("editForm");
Route::post("form-template-save","API\FormController@FormSave")->name("FormSave");
Route::get("form-create","API\FormController@createForm")->name("createForm");
Route::get("users-menu-list","API\UserController@listUsers")->name("listUsers");
Route::get("users-menu-create","API\UserController@createUser")->name("createUser")->middleware("auth-middleware");
Route::post("user-create-admin","API\UserController@createUserPost")->name("createUserPost")->middleware("auth-middleware");
Route::post("verify-user","API\AuthenticationController@verifyUser")->name("verifyUser");
Route::post("verify-pin","API\AuthenticationController@verifyemailpin")->name("verifyemailpin");
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
