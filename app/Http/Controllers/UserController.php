<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{


    /* public function register(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array) && !empty($params)){
            $params_array=array_map('trim',$params_array);

            $validate=\Validator::make($params_array,[
                'NOMBRE'=> 'required',
                'APELLIDOPATERNO' => 'required|alpha',
                'APELLIDOMATERNO' => 'required|alpha',
                'EMAIL' => 'required|unique:App\Models\User,EMAIL',
                'PASSWORD' => 'required'
            ]);

            if($validate->fails()){
                $data =array(
                    'status'=>'error',
                    'code' =>400,
                    'message'=>'Error en validacion de usuario',
                    'errors'=>$validate->errors()
                );


            }else{

                $pwd=hash('sha256',$params->PASSWORD);

                $user =new User();
                $user->NOMBRE=$params_array['NOMBRE'];
                $user->APELLIDOPATERNO=$params_array['APELLIDOPATERNO'];
                $user->APELLIDOMATERNO=$params_array['APELLIDOMATERNO'];
                $user->EMAIL=$params_array['EMAIL'];
                $user->PASSWORD=$pwd;

                $user->save();
                $data =array(
                    'status'=>'ok',
                    'code' =>200,
                    'message'=>'Usuario creado correctamente'
                );

            }
        }else{
            $data =array(
                'status'=>'error',
                'code' =>400,
                'message'=>'Debe enviar datos'

            );

        }
        return response()->json($data,$data['code']);
    } */

    public function login(Request $request){

        $jwtAuth=new \JwtAuth();
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        $validate=\Validator::make($params_array,[
            'IDUSUARIO' =>'required',
            'CLAVE'=>'required'
        ]);

        if($validate->fails()) {
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Faltan datos',
                'errors'=>$validate->errors()
            );
        }else{
       //     $pwd=hash('sha256',$params->CLAVE);
            $pwd=$params->CLAVE;
            $signup=$jwtAuth->signup($params->IDUSUARIO,$pwd);

            if(!empty($params->gettoken)){
                $signup=$jwtAuth->signup($params->IDUSUARIO,$pwd,true);
            }   
        }

        return response()->json($signup,200);
    }


   /*  public function update(Request $request){


        $token=$request->header('Authorization');
        $jwtAuth=new \JwtAuth();
        $checktoken=$jwtAuth->checkToken($token);

        $json=$request->input('json',null);
        $params_array=json_decode($json,true);

        if($checktoken &&!empty($params_array)){
            $user=$jwtAuth->checktoken($token,true);
            $validate = \Validator::make($params_array,[
                'NOMBRE'=> 'required|alpha',
                'APELLIDOPATERNO' => 'required|alpha',
                'APELLIDOMATERNO' => 'required|alpha',
                'EMAIL' => 'required|unique:USER'.$user->ID

            ]);
            unset($params_array['ID']);
            unset($params_array['PASSWORD']);
            unset($params_array['CREATED_AT']);
            unset($params_array['UPDATED_AT']);

            $user_update=User::where('ID',$user->ID)->update($params_array);

            $data =array(
                'code'=>200,
                'status'=>'ok',
                'user'=>$user,
                'changes'=>$params_array
            );
        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Usuario no identificado'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function destroy($id){

        $user=User::where('ID',$id)->delete();


        if(!empty($user)){


            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Regristro de usuario eliminado'
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'El usuario no existe'
            );
        }

        return response()->json($data,$data['code']);

    } */
}

