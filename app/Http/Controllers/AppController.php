<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\App;

class AppController extends Controller
{
    //
 
    public function index(){

        $applications=App::all();

        $data=array(
            'status' =>'ok',
            'code'=>200,
            'app'=>$applications
        );

        return response()->json($data,$data['code']);
    }

    public function show($id){
        $app=App::find($id);

        if(is_object($app)){
            $data=array(
                'status' =>'ok',
                'code'=>200,
                'app'=>$app
            );
        }else{
            $data=array(
                'status' =>'error',
                'code'=>400,
                'message' =>'No existe el App'
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
                'DESCRIPCION'=>'required',
                'NOMBREAPP'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'error'=>$validate->errors()
                );
            }else{
                $app=new App();
                $app->DESCRIPCION=$params_array['DESCRIPCION'];
                $app->NOMBREAPP=$params_array['NOMBREAPP'];
                $app->save();

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Aplicacion creada correctamente'
                );
            }

        }else{

            $data=array(
                'status'=>'error',
                'code' =>400,
                'message' =>'Debe ingresar datos'
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
                'NOMBREAPP'=>'required|alpha',
                'DESCRIPCION'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos a actualizar',
                    'errors'=>$validate->errors()
                );
            }else{

                $app_update=App::where('ID',$id)->update($params_array);
                $data=array(
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>'Aplicacion actualizada',
                    'app'=>$params_array
                );
            }



        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar un id de Aplicacion'
            );
        }

        return response()->json($data,$data['code']);




    }




}
