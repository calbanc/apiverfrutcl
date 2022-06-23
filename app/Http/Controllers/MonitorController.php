<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monitor;

class MonitorController extends Controller
{
    //
    public function index(){
        $monitor=Monitor::all();
        $data=array(
            'status'=>'ok',
            'code'=>200,
            'monitor'=>$monitor
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
                $monitor=new Monitor();
                $monitor->IDEMPRESA=$params_array['IDEMPRESA'];
                $monitor->DESCRIPCION=$params_array['DESCRIPCION'];
                $monitor->CANTIDAD=$params_array['CANTIDAD'];
                $monitor->SW_OPERATIVO=$params_array['SW_OPERATIVO'];
                $monitor->save();

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Monitor registrado correctamente',
                    'monitor'=>$params_array
                );
            }



        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar datos de monitor'
            );
        }


        return response()->json($data,$data['code']);
    }

    public function destroy($id){
        $monitor=Monitor::where('ID',$id)->delete();

        if(!empty($monitor)){
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Registro de monitor eliminado correctamente'
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'El monitor no existe'
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
                $monitor_update=Monitor::where('ID',$id)->update($params_array);
                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Monitor actualizado correctamente',
                    'monitor'=>$params_array,
                    'changes'=>$monitor_update
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
        $monitor=Monitor::find($id);

        if(!empty($monitor)){
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Monitor encontrado',
                'monitor'=>$monitor
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Monitor no se encuentra registrado'

            );
        }

        return response()->json($data,$data['code']);
    }



}
