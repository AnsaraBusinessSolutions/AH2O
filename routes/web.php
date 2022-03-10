<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get("login-page/{loginrole?}","API\AuthenticationController@loginPage")->name("login-page");
Route::get("/init","HomeController@init")->name("init");
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home-page-content', 'HomeController@home_page_content')->name('home_page_content')->middleware("auth-middleware")->middleware("role:superadmin,admin,user");
Route::get('/createuser','UserController@createUser')->name("create_user")->middleware("auth-middleware")->middleware("role:superadmin");
Route::post("/createuserdata","UserController@createUserData")->name("createuserdata")->middleware("auth-middleware")->middleware("role:superadmin");
Route::get("/create_menu","MenuController@createMenu");
Route::post("/create_menu_data","MenuController@createMenuData")->name("createMenuData");
Route::get("verify-email","API\AuthenticationController@verifyEmail")->name("verifyEmail");
Route::get("/verify-pin/{loginuser?}","API\AuthenticationController@verifymailview")->name("verifymailview");
Route::get("/notification",function(){
	event(new \App\Events\NotificationEvent('User'));
	return 'Event is now sent';
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
