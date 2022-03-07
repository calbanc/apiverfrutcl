<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Desktop;

class DesktopController extends Controller
{
    public function index(){
        $desktop=Desktop::all();
        $data = array(
            'status'=>'ok',
            'code'=>200,
            'desktop'=>$desktop
        );
        return response()->json($data,$data['code']);
    }
    public function show($id){
       
        //$notebook=Notebook::with('empresa')->where('ID',$id)->toSql();
  
      $desktop=Desktop::find($id);
     //->load('empresa');


        if(!empty($desktop)){
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Desktop encontrado',
                'desktop'=>$desktop
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Desktop no se encuentra registrado'
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
                'PROCESADOR'=>'required',
                'RAM'=>'required',
                'SSDD'=>'required',
                'SW_OPERATIVO'=>'required',
                'SW_ARRENDADO'=>'required',
                'COD_EMP'=>'required',
                'SERIE'=>'required|unique:App\Models\Desktop,SERIE'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error en Validacion de datos',
                    'error'=>$validate->errors()
                );
            }else{
                $desktop=new Desktop();
                $desktop->PROCESADOR=$params_array['PROCESADOR'];
                $desktop->RAM=$params_array['RAM'];
                $desktop->SSDD=$params_array['SSDD'];
                $desktop->SW_OPERATIVO=$params_array['SW_OPERATIVO'];
                $desktop->SW_ARRENDADO=$params_array['SW_ARRENDADO'];
                $desktop->COD_EMP=$params_array['COD_EMP'];
                $desktop->SERIE=$params_array['SERIE'];
                $desktop->save();

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Desktop creado correctamente'
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
                'PROCESADOR'=>'required',
                'RAM'=>'required',
                'SSDD'=>'required',
                'SW_OPERATIVO'=>'required',
                'SW_ARRENDADO'=>'required',
                'COD_EMP'=>'required',

            ]);

            if($validate->fails()) {
                $data=array(
                    'status' =>'error',
                    'code'=>400,
                    'message' =>'Error en validacion de datos',
                    'error'=>$validate->errors()
                );
            }else{
                unset($params_array['CREATED_AT']);
                unset($params_array['UPDATED_AT']);
                unset($params_array['SERIE']);
                unset($params_array['ID']);

                $desktop_update=Desktop::where('ID',$id)->update($params_array);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Desktop actualizado',
                    'desktop'=>$desktop_update,
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

        $desktop=Desktop::where('ID',$id)->delete();


        if(!empty($desktop)){


            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Regristro de desktop eliminado'
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'El desktop no existe'
            );
        }

        return response()->json($data,$data['code']);

    }

}
