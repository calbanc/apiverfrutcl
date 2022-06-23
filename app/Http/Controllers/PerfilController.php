<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfil;
use App\Models\App;
use App\Helpers\JwtAuth;

class PerfilController extends Controller
{

     public function __construct(){
        $this->middleware('api.auth');
    }

    public function index(){
        $perfil=Perfil::all()->load('app')
                            ->load('user');

        if(!is_object($perfil)){
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Perfil no encontrado'
            );
        }else{
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'perfil'=>$perfil
            );
        }

        return response()->json($data,$data['code']);

    }

    public function show($id){
        $perfil=Perfil::find($id)->load('app')
                                ->load('user');



        if(!is_object($perfil)||isnull($perfil)){
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Perfil no encontrado'
            );
        }else{
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'perfil'=>$perfil
            );
        }

        return response()->json($data,$data['code']);
    }

    public function buscaperfilusuario(Request $request){

        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);
        $usuario=$this->getIdentity($request);
        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'IDAPP'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>404,
                    'mesage'=>'ERROR EN VALIDACION',
                    'error'=>$validate->errors()
                );

            }else{

                $perfil=Perfil::with('app')->where('IDUSUARIO',$usuario->IDUSUARIO)
                                         //   ->where('IDAPP',$params_array['IDAPP'])
                                            ->get();


                                            $data=array(
                                                'status'=>'ok',
                                                'code'=>200,
                                                'perfil'=>$perfil
                                            );
                if(!is_object($perfil)||empty($perfil)){

                    $data=array(
                        'status'=>'error',
                        'code'=>404,
                        'mesage'=>'Sin accesos'
                    );

                }else{

                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'perfil'=>$perfil
                    );
                }
            }

        }else{
            $data=array(
                'status'=>'error',
                'code'=>404,
                'mesage'=>'Debe ingresar elementos'
            );

        }





        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);
        $usuario=$this->getIdentity($request);
        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'IDAPP'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>404,
                    'mesage'=>'ERROR EN VALIDACION',
                    'error'=>$validate->errors()
                );

            }else{

                $perfil=Perfil::with('app')->where('IDUSUARIO',$usuario->IDUSUARIO)
                                          ->where('IDAPP',$params_array['IDAPP'])
                                            ->get();




                if(is_null($perfil)){

                    $data=array(
                        'status'=>'error',
                        'code'=>404,
                        'mesage'=>'Sin accesos'
                    );

                }else{

                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'perfil'=>$perfil
                    );
                }
            }

        }else{
            $data=array(
                'status'=>'error',
                'code'=>404,
                'mesage'=>'Debe ingresar elementos'
            );

        }

        return response()->json($data,$data['code']);





    }
    private function getIdentity($request){
        //metodo para conseguir el usuario identificado
        $jwtAuth=new JwtAuth();
        $token=$request->header('Authorization',null);
        $user=$jwtAuth->checkToken($token,true);
        return $user;
    }

    public function store(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'IDAPP'=>'required',
                'IDUSUARIO'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error en datos ingresados',
                    'error'=>$validate->errors()
                );
            }else{

                $registrado=Perfil::where(
                    ['IDAPP'=>$params_array['IDAPP'],
                    'IDUSUARIO'=>$params_array['IDUSUARIO']])->first();
                if(empty($registrado)){

                    $perfil=new Perfil();
                    $perfil->IDAPP=$params_array['IDAPP'];
                    $perfil->IDUSUARIO=$params_array['IDUSUARIO'];

                    $perfil->save();
                    $data = array(
                        'status'=>'ok',
                        'code'=>200,
                        'message'=>'Perfil registrado correctamente'
                    );
                }else{
                    $data=array(
                        'status'=>'error',
                        'code'=>400,
                        'messagee'=>'Usuario ya cuenta con acceso a la Aplicacion'
                    );
                }

           }

        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar datos para guardar'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function update(Request $request,$id){
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'IDAPP'=>'required',
                'IDUSUARIO'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error en datos ingresados',
                    'error'=>$validate->errors()
                );
            }else{
                $where =[
                    'ID'=>$id
                ];
                $perfil =Perfil::updateOrCreate($where,$params_array);


                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Perfil Actualizado',
                    'perfil'=>$perfil,
                    'changes'=>$params_array
                );

            }

        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar datos'
            );
        }

        return response()->json($data,$data['code']);
    }


    public function destroy($id){

        $perfil=Perfil::where('ID',$id)->delete();


        if(!empty($perfil)){


            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Regristro de perfil eliminado'
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'El perfil no existe'
            );
        }

        return response()->json($data,$data['code']);

    }



}
