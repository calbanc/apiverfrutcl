<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\ZonasRemu;

class ZonasRemuController extends Controller
{
    //

    public function index(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $cod_emp=$params_array['IdEmpresa'];
            
          
            $zonas=ZonasRemu::select('IdZona','Nombre')
                                 -> where('IdEmpresa',$cod_emp)
                                  ->get();

           // $zonas=DB::connection('sqlsrv4')->select($sql);  
                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'zonas'=>$zonas
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