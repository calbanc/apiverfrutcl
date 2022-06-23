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



            $info=array("Database"=>"erpfrusys","UID"=>'reporte',"PWD"=>'abc.123456',"CharacterSet"=>"UTF-8");
            $hostname_localhost="192.168.2.210";
            $conexion = sqlsrv_connect($hostname_localhost,$info);


            $COD_EMP=$params_array['COD_EMP'];
            $COD_TEM=$params_array['COD_TEM'];

            if(is_numeric($COD_EMP)&&$COD_EMP<>'9'){
                $consultaempresa="SELECT COD_EMP FROM EMPRESAS WHERE ID_EMPRESA_REM='{$COD_EMP}'";
                $resultadoempresa=sqlsrv_query($conexion,$consultaempresa);

                if($registroempresa=sqlsrv_fetch_array($resultadoempresa)){
                    $COD_EMP=$registroempresa['COD_EMP'];
                }
            }else{
                if($COD_EMP=='9'){
                    $COD_EMP='ARAP';
                }
            }

            $zonas=Zonas::select('ZON', 'NOM_ZON')->where('COD_EMP',$COD_EMP)
            ->where('COD_TEM',$COD_TEM)
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
