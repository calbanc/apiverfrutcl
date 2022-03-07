<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notebook;

class NotebookController extends Controller
{

    public function index(){
        $notebook = Notebook::all();
        $data=array(
            'status'=>'ok',
            'code'=>200,
            'notebook'=>$notebook
        );
        return response()->json($data,$data['code']);
    }

    public function show($id){
       
        //$notebook=Notebook::with('empresa')->where('ID',$id)->toSql();
  
      $notebook=Notebook::find($id);
     //->load('empresa');


        if(!empty($notebook)){
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Notebook encontrado',
                'notebook'=>$notebook
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Notebook no se encuentra registrado'
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
                'COD_EMP'=>'required',
                'MARCA'=>'required',
                'MODELO'=>'required',
                'RAM'=>'required',
                'SSDD'=>'required',
                'PANTALLA'=>'required',
                'SW_ARRENDADO'=>'required',
                'SW_OPERATIVIDAD'=>'required',
                'SERIE'=>'required|unique:App\Models\Notebook,SERIE'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'errors'=>$validate->errors()
                );
            }else{
                $notebook=new Notebook();
                $notebook->COD_EMP=$params_array['COD_EMP'];
                $notebook->MARCA=$params_array['MARCA'];
                $notebook->MODELO=$params_array['MODELO'];
                $notebook->RAM=$params_array['RAM'];
                $notebook->SSDD=$params_array['SSDD'];
                $notebook->PANTALLA=$params_array['PANTALLA'];
                $notebook->SW_ARRENDADO=$params_array['SW_ARRENDADO'];
                $notebook->SW_OPERATIVIDAD=$params_array['SW_OPERATIVIDAD'];
                $notebook->SERIE=$params_array['SERIE'];
                $notebook->save();

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Notebook registrada correctamente',
                    'notebook'=>$params_array
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
        $notebook=Notebook::where('ID',$id)->delete();   
        
        if(!empty($notebook)){
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Notebook eliminado correctamente'
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Notebook no encontrado'
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
                'COD_EMP'=>'required',
                'MARCA'=>'required',
                'MODELO'=>'required',
                'RAM'=>'required',
                'SSDD'=>'required',
                'PANTALLA'=>'required',
                'SW_ARRENDADO'=>'required',
                'SW_OPERATIVIDAD'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                     'code'=>404,
                     'message'=>'Error validando datos de notebook',
                     'error'=>$validate->errors()
                );
            }else{
                unset($params_array['CREATED_AT']);
                unset($params_array['UPDATED_AT']);
                unset($params_array['SERIE']);
                unset($params_array['ID']);
                $notebook_update=Notebook::where('ID',$id)->update($params_array);
                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Notebook actualizado correctamente',
                    'notebook'=>$params_array,
                    'changes'=>$notebook_update
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
}
