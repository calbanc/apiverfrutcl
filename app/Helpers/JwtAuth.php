<?php
namespace App\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JwtAuth{

    public $key;

    public function __construct(){
        $this->key='grupo_verfrut_frutsys';
    }

    public function signup($email,$password,$gettoken=null){
    //buscar si existe el usuario con sus credenciales
        $user=User::where([
          'IDUSUARIO' => $email,
          'CLAVE'=>$password])->first();

        $signup=false;

        if(is_object($user)){
            $signup=true;
        }

        if($signup){
            $token=array(
                'IDUSUARIO'    => $user->IDUSUARIO,
                'iat'=>time(),
                 'exp'=>time()+(1*60*60*24*7)
            );

            $jwt=JWT::encode($token,$this->key,'HS256');
            $decode=JWT::decode($jwt,$this->key,['HS256']);

            

            if(is_null($gettoken)){
            //    return $data=$jwt;

            return $data=array(
                'status'=>'ok',
                'code'=>200,
                'token'=>$jwt
            );
            }else{
                return $data=$decode;
            }
        }else{
            $data=array(
                'status'=>'error',
                'code'=>404,
                'message'=>'login incorrecto'
            );
        }



        return $data;
    }
    public function checkToken($token,$getidentity=false){
        $auth=false;
        try{
            $token=str_replace('"','',$token);
            $decode=JWT::decode($token,$this->key,['HS256']);
        }catch(\UnexpectedValueException $e){
            $auth=false;
        }catch(\DomainException $e){
            $auth=false;
        }

        if(!empty($decode)&& is_object($decode) && isset($decode->IDUSUARIO)){
            $auth=true;
        }else{
            $auth=false;
        }

        if($getidentity){
            return $decode;
        }
        return $auth;

    }


}
