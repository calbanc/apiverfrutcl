<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TitDatos;
use Illuminate\Support\Facades\DB;

class TitDatosController extends Controller
{

    public function index(){

        $planillas=TitDatos::all();

        $data=array(
            'status' =>'ok',
            'code'=>200,
            'planillas'=>$planillas
        );

        return response()->json($data,$data['code']);
    }

    public function listbyuser(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'IDTEMPORADA'=>'required',
                'USUARIO'=>'required'
            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'error'=>$validate->errors()
                );
            }else{

                    $planillas=TitDatos::all()
                                    ->where('IDEMPRESA',$params_array['IDEMPRESA'])
                                    ->where('IDTEMPORADA',$params_array['IDTEMPORADA'])
                                    ->where('USUARIO',$params_array['USUARIO']);


                    if(is_object($planillas)){
                        $data=array(
                            'status'=>'ok',
                            'code'=>200,
                            'planillas'=>$planillas
                        );
                    }else{
                        $data=array(
                            'status'=>'error',
                            'code'=>400,
                            'message'=>'Usuario no cuenta con planillas registradas'
                        );
                    }

            }

        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe enviar datos a buscar'
            );

        }
        return response()->json($data,$data['code']);
    }

    public function create(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'IDTEMPORADA'=>'required',
                'IDREGISTRO'=>'required',
                'IDPLANILLA'=>'required',
                'USUARIO'=>'required',
                'FECHA'=>'required',
                'HORA'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'error'=>$validate->errors()
                );
            }else{

                $planilla=new TitDatos();
                $planilla->IDEMPRESA=$params_array['IDEMPRESA'];
                $planilla->IDTEMPORADA=$params_array['IDTEMPORADA'];
                $planilla->IDREGISTRO=$params_array['IDREGISTRO'];
                $planilla->IDPLANILLA=$params_array['IDPLANILLA'];
                $planilla->USUARIO=$params_array['USUARIO'];
                $planilla->FECHA=$params_array['FECHA'];
                $planilla->HORA=$params_array['HORA'];
                $planilla->save();

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Planilla creada correctamente'
                );

            }
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe enviar datos'
            );
        }

        return response()->json($data,$data['code']);

    }
}
