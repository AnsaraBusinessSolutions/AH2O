<?php

namespace App\Http\Middleware;
use DB;
use Auth;
use Closure;
use JWTAuth;
use Session;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use App\Models\AuthenticationMethod;
use Laravel\Passport\TokenRepository;


class APIAuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
	    $auth =AuthenticationMethod::select("method")->where("active",1)->orderBy("id","asc")->get();
	    $authorized = 0;
	    if($auth[0]){
		    if($auth[0]["method"]=="passport"){
				$access_token = $request->header("Authorization");
				if(session()->has("Session:".substr($access_token,-10))){
					$authorized = 1;
				}
				else
				$authorized = $this->handlePassportAuthorization($access_token);


			  //  $authorized = Auth::guard("api-passport")->check();	
			}
		    if($auth[0]["method"]=="jwt"){

				try{
				$token = $request->header("Authorization");
				//echo session()->get("Session:".substr($token,-10));
				if(session()->has("Session:".substr($token,-10))==false){
			    if(!JWTAuth::parseToken()->authenticate())
				    $authorized = 0;
			    else
				    $authorized = 1;
				
					$user = JWTAuth::toUser($token);
				session()->put("Session:".substr($token,-10),$user);
				}
				else{
					$authorized = 1;
				}
				}
				catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
					abort(401);
				}
				catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
					abort(401);
				}

				    //$authorized = Auth::guard("api")->check();	
		
			}
		if($auth[0]["method"]=="default_auth"){
			$authorized = Auth::check();	
		}
	    }
	    if($authorized)	    
		    return $next($request);
	    else
		    return abort(401);
    }
	public function handlePassportAuthorization($access_token){
						// break up the token into its three respective parts
						$token_parts = explode('.', $access_token);
						$token_header = isset($token_parts[1])?$token_parts[1]:false;
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
						
						
						$authorized = 0;//a
						if(isset($token_data["jti"])){
						$authorized_token = DB::table("oauth_access_tokens")->select("id","expires_at","user_id")->where("id",$token_data["jti"])->get();
						if(count($authorized_token)>0){//'
							$expires_at= new DateTime($authorized_token[0]->expires_at);
							$currentTime = new DateTime();
							
							$authorized = $expires_at > $currentTime?1:0;

						}
						if($authorized){
							$user_detail = User::find($authorized_token[0]->user_id);
							session()->put("Session:".substr($access_token,-10),$user_detail);
						}
						}
						return $authorized;
					}
}
