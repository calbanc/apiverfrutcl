<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoLineaPk;

class TipoLineaPkController extends Controller
{
    //

    public function index(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $cod_emp=$params_array['COD_EMP'];
            $lineas=TipoLineaPk::select('TIPOLINEAPK.COD_LINEA', 'TIPOLINEAPK.NOM_LINEA')
            ->join('Empresas','Empresas.COD_EMP','=','TIPOLINEAPK.COD_EMP')
            ->where('Empresas.ID_EMPRESA_REM',$cod_emp)
            ->where('Empresas.SW_DESPLIEGUE_WEB','1')
            ->where('TIPOLINEAPK.COD_TEM',$params_array['COD_TEM'])
            ->get();

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'lineas'=>$lineas
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
