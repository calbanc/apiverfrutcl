<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpresaRemuneraciones;
use App\Models\TemporadaRemuneraciones;

class EmpresaRemuneracionesController extends Controller
{
    //
    public function index(){
        $empresa=EmpresaRemuneraciones::select('IdEmpresa', 'Nombre')

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
                'IdEmpresa'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Debe ingresar una empresa'
                );
            }else{
                $cod_emp=$params_array['IdEmpresa'];
                $temporada=TemporadaRemuneraciones::select('IdTemporada','Descripcion')
                                     -> where('IdEmpresa',$cod_emp)
                                   
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
