<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mouse;
class MouseController extends Controller
{
    //

    public function index(){
        $mouse=Mouse::all();
        $data=array(
            'status'=>'ok',
            'code'=>200,
            'mouse'=>$mouse
        );

        return response()->json($data,$data['code']);
    }
    public function show($id){
        $mouse=Mouse::find($id);
        if(!empty($mouse)){
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Mouse encontrado correctamente',
                'mouse'=>$mouse
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Mouse no encontrado'
            );
        }
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
                'CANTIDAD'=>'required|numeric',
                'SW_OPERATIVO'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'error'=>$validate->errors()
                );

            }else{
                $mouse=new Mouse();
                $mouse->IDEMPRESA=$params_array['IDEMPRESA'];
                $mouse->DESCRIPCION=$params_array['DESCRIPCION'];
                $mouse->CANTIDAD=$params_array['CANTIDAD'];
                $mouse->SW_OPERATIVO=$params_array['SW_OPERATIVO'];
                $mouse->save();

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Mouse registrado correctamente'
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

    public function update(Request $request,$id){
        $json=$request->input('json',null);

        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'DESCRIPCION'=>'required',
                'CANTIDAD'=>'required|numeric',
                'SW_OPERATIVO'=>'required'
            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validate datos',
                    'error'=>$validate->errors()
                );
            }else{
                $mouse_update=Mouse::where('ID',$id)->update($params_array);
                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Mouse actualizado correctamente',
                    'mouse'=>$mouse_update,
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
        $mouse=Mouse::where('ID',$id)->delete();

        if(!empty($mouse)){
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Mouse eliminado correctamente'
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Mouse no encontrado'
            );
        }
        return response()->json($data,$data['code']);
    }
}
