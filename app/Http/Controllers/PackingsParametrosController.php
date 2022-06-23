<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PackingParametros;

class PackingsParametrosController extends Controller
{
    //


    public function index(){


        $aplicacion=PackingParametros::all();

        $usuario=this.getIdentity();
        echo $usuario;
        die();
        $data=array(
            'status'=>'ok',
            'code'=>200,
            'Camiones'=>$aplicacion
        );

        return response()->json($data,$data['code']);
    }

    public function getbyuseremptem(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);
        $token=$request->header('Auth');
        $jwtAuth=new \JwtAuth();
        $checktoken=$jwtAuth->checkToken($token);

        $user=$jwtAuth->checktoken($token,true);
        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required'
            ]);
            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Fallo en validacion de datos enviados'
                );
            }else{
                $user=$jwtAuth->checktoken($token,true);
                if($user->IDUSUARIO=='testios'){
                    $parametros=array(
                            'COD_PACK'=>"P041",
                            'COD_TEM'=>'21',
                            'COD_EMP'=>'VERF',
                            'ZON'=>'P',
                            'COD_FRI'=>'F041',
                            'IDUSUARIO'=>'testios',
                            'COD_LINEA'=>'COMP'

                    );
                }else{
                    $parametros=PackingParametros::where('COD_EMP',$params_array['COD_EMP'])
                    ->where('COD_TEM',$params_array['COD_TEM'])
                    ->where('IDUSUARIO',$user->IDUSUARIO)
                    ->get();

                }
                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'parametros'=>$parametros,

                );
            }
        }else{
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Debe enviar datos'
            );
        }
        return response()->json($data,$data['code']);
    }



    // public function searchbyemptemuser(Request $request){
    //     $json=$request->input('json',null);
    //     $params=json_decode($json);
    //     $params_array=json_decode($json,true);

    //     if(!empty($params_array)){
    //         $validate=\Validator::make($params_array,[
    //             'COD_EMP'=>'required',
    //             'COD_TEM'=>'required'
    //         ]);

    //         if($validate->fails()){
    //             $data=array(
    //                 'code'=>400,
    //                 'status'=>'error',
    //                 'error'=>$validate->errors()
    //             );
    //         }else{
    //             $camiones=RecepcionCamiones::where('COD_EMP',$params_array['COD_EMP'])
    //                                         ->where('COD_TEM',$params_array['COD_TEM'])
    //                                         ->get();
    //             if(!empty($camiones)){
    //                 $data=array(
    //                     'status'=>'ok',
    //                     'code'=>200,
    //                     'camiones'=>$camiones
    //                 );
    //             }else{
    //                 $data=array(
    //                     'status'=>'error',
    //                     'code'=>400,
    //                     'message'=>'No hay camiones registrados'
    //                 );
    //             }
    //         }
    //     }else{
    //         $data=array(
    //             'status'=>'error',
    //             'code'=>400,
    //             'message'=>'Debe enviar datos'
    //         );
    //     }
    //     return response()->json($data,$data['code']);
    // }

    // public function listbyempzon(Request $request){
    //     $json=$request->input('json',null);
    //     $params_array=json_decode($json);
    //     $params_array=json_decode($json,true);

    //     if(!empty($params_array)){
    //         $validate=\Validator::make($params_array,[
    //             'COD_EMP'=>'required',
    //             'COD_TEM'=>'required',
    //             'ZON'=>'required'
    //         ]);



    //         if($validate->fails()){
    //             $data=array(
    //                 'status'=>'error',
    //                 'code'=>400,
    //                 'message'=>'Error en validacion de datos',
    //                 'errors'=>$validate->errors()
    //             );
    //         }else{
    //             $camiones=RecepcionCamiones::where('COD_EMP',$params_array['COD_EMP'])
    //                                         ->where('COD_TEM',$params_array['COD_TEM'])
    //                                         ->where('ZON',$params_array['ZON'])
    //                                         ->get();
    //             if(!empty($camiones)){
    //                 $data=array(
    //                     'status'=>'ok',
    //                     'code'=>200,
    //                     'camiones'=>$camiones
    //                 );
    //             }else{
    //                 $data=array(
    //                     'status'=>'error',
    //                     'code'=>400,
    //                     'message'=>'Camiones no encontrados'
    //                 );
    //             }
    //         }

    //     }else{
    //         $data=array(
    //             'status'=>'ok',
    //             'code'=>400,
    //             'message'=>'Error debe enviar datos'
    //         );
    //     }

    //     return response()->json($data,$data['code']);
    // }
}
