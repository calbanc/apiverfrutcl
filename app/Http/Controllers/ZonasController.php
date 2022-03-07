<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zonas;

class ZonasController extends Controller
{
    //

    public function index(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $cod_emp=$params_array['COD_EMP'];
            $zonas=Zonas::select('ZON', 'NOM_ZON')->where('COD_EMP',$cod_emp)
            ->where('COD_TEM','21')
            ->get();

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
