<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teclado;
class TecladoController extends Controller
{
    public function index(){
        $teclado=Teclado::all();
        $data=array(
            'status'=>'ok',
            'code'=>200,
            'teclado'=>$teclado
        );

        return response()->json($data,$data['code']);
    }
    public function store(Request $request){
        $json=$request->input('json',null);

        $params=json_decode($json);
        $params_array=json_decode($json,true);
        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'DESCRIPCION'=>'required',
                'CANTIDAD'=>'required',
                'SW_OPERATIVO'=>'required'
            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos ingresados',
                    'errors'=>$validate->errors()
                );
            }else{
                $teclado=new Teclado();
                $teclado->IDEMPRESA=$params_array['IDEMPRESA'];
                $teclado->DESCRIPCION=$params_array['DESCRIPCION'];
                $teclado->CANTIDAD=$params_array['CANTIDAD'];
                $teclado->SW_OPERATIVO=$params_array['SW_OPERATIVO'];
                $teclado->save();

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Teclado registrado correctamente',
                    'teclado'=>$params_array
                );
            }



        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar datos de teclado'
            );
        }


        return response()->json($data,$data['code']);
    }

    public function destroy($id){
        $teclado=Teclado::where('ID',$id)->delete();

        if(!empty($teclado)){
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Registro de teclado eliminado correctamente'
            );
        }else{
            $data=array(
                'status'=>'ok',
                'code'=>400,
                'message'=>'El teclado no existe'
            );
        }

        return response()->json($data,$data['code']);
    }
    public function update($id,Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'DESCRIPCION'=>'required',
                'CANTIDAD'=>'required',
                'SW_OPERATIVO'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando los datos ingresados',
                    'error'=>$validate->errors()
                );
            }else{
                $teclado_update=Teclado::where('ID',$id)->update($params_array);
                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Teclado actualizado correctamente',
                    'teclado'=>$params_array,
                    'changes'=>$teclado_update  
                );

            }
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Dede ingresar los datos para actualizar'
            );
        }

        return response()->json($data,$data['code']);
    }
    public function show($id){
        $teclado=Teclado::find($id);

        if(!empty($teclado)){
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Monitor encontrado',
                'teclado'=>$teclado
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Teclado no se encuentra registrado'

            );
        }

        return response()->json($data,$data['code']);
    }


}
