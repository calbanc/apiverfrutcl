<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Temporada;

class EmpresaController extends Controller
{
    //

    public function index(){
        $empresa=Empresa::select('COD_EMP', 'NOM_EMP')
       // ->where('SW_DESPLIEGUE_WEB','1')
        ->get();

            $data=array(
                'status'=>'ok',
                'code'=>200,
                'empresa'=>$empresa
            );


       return response()->json($data,$data['code']);

    }

    public function temporada(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Debe ingresar una empresa'
                );
            }else{
                $cod_emp=$params_array['COD_EMP'];
                $temporada=Temporada::select('COD_TEM','DESCRIPCION')
                                     -> where('COD_EMP',$cod_emp)
                                     ->orderBy('ORDEN','DESC')
                                      ->get();
                $data=array(
                    'code'=>200,
                    'status'=>'ok',
                    'temporada'=>$temporada) ;
                 
            }
    
        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'debe enviar valores'
            );
        }
       
        return response()->json($data,$data['code']);
    }

    

}
