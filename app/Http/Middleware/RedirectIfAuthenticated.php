<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\AuthenticationMethod;
use App\Http\Controllers\PassportAuthControl;
use App\Http\Controllers\JWTAuthController;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
	    $loginBackendSetting = $request->loginBackendSetting=='true' || $request->loginBackendSetting==true?$request->loginBackendSetting:0;
	    if($loginBackendSetting==true){
			//    echo "in the condition";
			//exit;    
		    $response = "";
		    $authentication_method = AuthenticationMethod::select("method")->where("active",1)->orderBy("id","asc")->get();
		    if($authentication_method[0] && $authentication_method[0]["method"]!=""){
			if($authentication_method[0]["method"]=="passport"){
				$response=(new PassportAuthControl())->login($request);
			}
			else if($authentication_method[0]["method"]=="jwt"){
				$response = (new JWTAuthController())->login($request);
			}
			else{
				//Passport considered aas  default authentication method
				$response=(new PassportAuthControl())->login($request);

			}
		    }
		    //return $response;
	    		if(Auth::guard($guard)->check()){
				return response()->json(["id"=>1,"message"=>"login successfull"]);
			}
			else{
				return response()->json(["id"=>2,"message"=>"login failed, Invalid credentials"]);
			}
	    }
	    else{    
        		if (Auth::guard($guard)->check()) {
            			return redirect(RouteServiceProvider::HOME);
        		}

			return $next($request);
	    }
    }
}
