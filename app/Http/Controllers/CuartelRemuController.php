<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cuartel;

class CuartelRemuController extends Controller
{
    //

    public function index(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $cod_emp=$params_array['IdEmpresa'];
            $idzona=$params_array['IdZona'];
            
            $cuartel=Cuartel::select('IdCuartel','Nombre')
                            ->where('IdEmpresa',$cod_emp)
                            ->where('IdZona',$idzona)
                            ->get();
                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'cuarteles'=>$cuartel
                );

        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar una empresa'
            );
        }
       return response()->json($data,$data['code']);

    }

    
}