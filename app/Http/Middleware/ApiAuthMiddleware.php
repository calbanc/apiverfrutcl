<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token=$request->header('Auth');
        $jwtAuth=new \JwtAuth();
        $checktoken=$jwtAuth->checkToken($token);
      
        if($checktoken){
            return $next($request);
        }else{
            $data=array(
                'code'=>404,
                'status'=>'error',
                'message'=>'El usuario no esta identificado correctamente.',
                'token'=>$token
            );
            return response()->json($data,$data['code']);
        }

    }
}
