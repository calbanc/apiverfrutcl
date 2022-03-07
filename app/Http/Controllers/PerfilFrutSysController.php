<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerfilFrutSys;
use App\Helpers\JwtAuth;

class PerfilFrutSysController extends Controller
{
    //


    public function find(Request $request){
        $token=$request->header('Auth');
        $jwtAuth=new \JwtAuth();
        $checktoken=$jwtAuth->checkToken($token);
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $user=$jwtAuth->checkToken($token,true);
            $aplicacion=$params_array['IdAplicacion'];
            $usuario=$user->IDUSUARIO;

            $data=array(
              'status'=>'ok',
                'code'=>200,
                'usuario'=>$usuario,
                'aplicacion'=>$aplicacion
            );


        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar datos'
            );
        }
        return response()->json($data,$data['code']);
    }
    public function permisosapp(Request $request){
        //dlajskdj
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);
     
        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'App'=>'required',
                'IdEmpresa'=>'required'
            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Debe enviar datos ',
                    'errors'=>$validate->errors()
                );
            }else{
                $token=$request->header('Auth');
                $jwtAuth=new \JwtAuth();
                $checktoken=$jwtAuth->checkToken($token);
        
                if($checktoken){
                    $usuario=$this->getIdentity($request);
        
                   $idusuario=$usuario->IDUSUARIO;
                   $idapp=$params_array['App'];
                   $idempresa=$params_array['IdEmpresa'];
                   
                    
                     $perfil=PerfilFrutSys::select('Perfil.IdMenu','Perfil.IdAplicacion','Perfil.IdEmpresa','Perfil.IdZona','Aplicacion.Descripcion')
                                        ->join('Aplicacion','Perfil.IdAplicacion','=','Aplicacion.IdAplicacion')
                                        ->join('Menu_Perfiles','Perfil.IdMenu','=','Menu_Perfiles.IdMenu')
                                        ->where('Perfil.IdUsuario',$idusuario)
                                        ->where('Perfil.IdEmpresa',$idempresa)
                                        ->where('Aplicacion.IdAplicacion',$idapp)
                                        ->orderby('Menu_Perfiles.Orden')
                                        ->get();
                    
                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'perfil'=>$perfil
                    );
               
                }else{
                    $data=array(
                        'status'=>'error',
                        'code'=>400,
                        'message'=>'error en usuario'
                    );
                }
            }

        }else{
                 $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe enviar datos '
            );
        }

      
        return response()->json($data,$data['code']);
    }

    public function aplicacionesusuario(Request $request){
        $token=$request->header('Auth');
        $jwtAuth=new \JwtAuth();
        $checktoken=$jwtAuth->checkToken($token);
        if($checktoken){
            $usuario=$this->getIdentity($request);
            $idusuario=$usuario->IDUSUARIO;
            $perfil=PerfilFrutSys::select('Perfil.IdAplicacion','Aplicacion.Nombre')
                                ->join('Aplicacion','Perfil.IdAplicacion','=','Aplicacion.IdAplicacion')
                                ->where('Perfil.IdUsuario',$idusuario)
                                ->where('Aplicacion.AppMovil','1')
                                ->distinct()
                                ->get();

            $data=array(
                'status'=>'ok',
                'code'=>200,
                'perfil'=>$perfil
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'error en usuario'
            );
        }
        return response()->json($data,$data['code']);
    }

   

    private function getIdentity($request){
        //metodo para conseguir el usuario identificado
        $jwtAuth=new JwtAuth();
        $token=$request->header('Auth',null);
        $user=$jwtAuth->checkToken($token,true);
        return $user;
    }

}
