<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Telefono;

class TefonoController extends Controller
{


    public function index(){
        $telefono=Telefono::all();
        $data=array(
            'status'=>'ok',
            'code'=>200,
            'telefono'=>$telefono
        );

        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        $json=$request->input('json',null);

        $params=json_decode($json);
        $params_array=json_decode($json,true);
        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'IMEI'=>'required|numeric|unique:App\Models\Telefono,IMEI',
                'MARCA'=>'required',
                'MODELO'=>'required',
                'EMAIL'=>'required|email',
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
                $telefono=new Telefono();
                $telefono->COD_EMP=$params_array['COD_EMP'];
                $telefono->IMEI=$params_array['IMEI'];
                $telefono->MARCA=$params_array['MARCA'];
                $telefono->MODELO=$params_array['MODELO'];
                $telefono->EMAIL=$params_array['EMAIL'];
                $telefono->SW_OPERATIVO=$params_array['SW_OPERATIVO'];
                $telefono->save();

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Telefono registrado correctamente',
                    'telefono'=>$params_array
                );
            }



        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar datos de telefono'
            );
        }


        return response()->json($data,$data['code']);
    }

    public function destroy($id){
        $teclado=Telefono::where('ID',$id)->delete();

        if(!empty($teclado)){
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Registro de telefono eliminado correctamente'
            );
        }else{
            $data=array(
                'status'=>'ok',
                'code'=>400,
                'message'=>'El telefono no existe'
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
                'COD_EMP'=>'required',
                'MARCA'=>'required',
                'MODELO'=>'required',
                'EMAIL'=>'required|email',
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

                unset($params_array['CREATED_AT']);
                unset($params_array['UPDATED_AT']);
                unset($params_array['IMEI']);
                unset($params_array['ID']);

                $telefono_update=Telefono::where('ID',$id)->update($params_array);
                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Teclado actualizado correctamente',
                    'telefono'=>$params_array,
                    'changes'=>$telefono_update
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
        $telefono=Telefono::find($id);

        if(!empty($telefono)){
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Telefono encontrado',
                'telefono'=>$telefono
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Telefono no se encuentra registrado'

            );
        }

        return response()->json($data,$data['code']);
    }
}
