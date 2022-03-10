<?php

namespace App\Http\Middleware;
use App\Models\AuthenticationMethod;
use Closure;
use JWTAuth;

class VerifyRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ... $role)
    {
	//	echo \Auth::user()->id." ".$role;
	//	exit;
                $method = AuthenticationMethod::select("method")->where("active",1)->orderBy("id","asc")->get();
                $exists = 0;
                if(isset($method[0]) && $method[0]->method=="passport"){
                $access_token = $request->header("Authorization");
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
        $exists = \App\User::leftJoin("roles","users.role","roles.id")->whereIn("roles.name",$role)->where("users.id","=",$usere_data)->exists();
            }
            
            else if(isset($method[0]) && $method[0]->method=="jwt"){
                $access_token = $request->header("Authorization");
                $usere_data = 0;
                try{
                if(JWTAuth::parseToken()->authenticate())
                    $usere_data = JWTAuth::toUser($access_token);
                    
                    
                
                //echo \App\User::leftJoin("roles","users.role","roles.id")->whereIn("roles.name",$role)->where("users.id","=",$usere_data)->toSql();
                //exit;
                 //if($user_id){
                     if(isset($usere_data->id))
                     $exists = 1;\App\User::leftJoin("roles","users.role","roles.id")->whereIn("roles.name",$role)->where("users.id","=",$usere_data->id)->exists();
                 //}   
                 //$exists = $exists_user;
                 
                }
                catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    \Auth::logout();
                    return response()->json(["message"=>"token expired, Login to app again.","result"=>"failed"]);
                }
                catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    \Auth::guard("api")->logout();
                    return response()->json(["message"=>"token is not valid, Login to app again.","result"=>"failed"]);
                }
                catch(\Tymon\JWTAuth\Exceptions\JWTException $e){
                    abort(403);
                }

            }
            else{
                
            }
            //echo $exists?"exists":"not esists";
            //exit;//s

	    if($exists)
	    	return $next($request);
	    else
		    abort(403);
    }
}
