<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecepcionCamiones;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
class VelocidadesController extends Controller
{

    public function recordbydatestation(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'FECHA'=>'required',
                'ESTACION'=>'required',

            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'validando datos',
                    'error'=>$validate->errors()
                );
            }else{
                $FECHA=$params_array['FECHA'];
                $ESTACION=$params_array['ESTACION'];

                $sql=" SELECT DISTINCT EMP.NOMBRE_ESTACION,B.CAPACIDAD,PA.LATITUD,PA.LONGITUD,PA.VELOCIDAD,PA.FECHA,PA.HORA ,TR.NOM_TRP
                FROM Estacion_Marcacion_Prueba EMP
                INNER JOIN [192.168.2.210].[bsis_rem_afr].[dbo].Buses B on B.IdEmpresa=EMP.idempresa and B.COD_BUS=EMP.codbus AND B.COD_TRP=EMP.COD_TRP
                LEFT JOIN [192.168.60.25].[BUSES].[dbo].Posiciones_Android PA ON PA.IdEstacion=EMP.ID
                INNER JOIN [192.168.2.210].[bsis_rem_afr].[dbo].Empresa E on E.IdEmpresa=EMP.idempresa
                INNER JOIN [192.168.2.210].[erpfrusys].[dbo].TRANSPORTISTAS TR ON TR.COD_EMP=E.COD_EMP_CONT AND TR.COD_TRP=EMP.COD_TRP AND TR.COD_TEM='22'
                WHERE sw_activo='1' and PA.Fecha='{$FECHA}' and EMP.NOMBRE_ESTACION='{$ESTACION}'
                ORDER BY HORA ";


                $marcaciones=DB::connection('sqlsrv5')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'datos encontrados',
                    'data'=>$marcaciones
                );

            }
        }

        return response()->json($data,$data['code']);
    }

    public function recordbydatetimestation(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'FECHA'=>'required',
                'ESTACION'=>'required',
                'DESDE'=>'required',
                'HASTA' =>'required'

            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'validando datos',
                    'error'=>$validate->errors()
                );
            }else{
                $FECHA=$params_array['FECHA'];
                $ESTACION=$params_array['ESTACION'];
                $DESDE=$params_array['DESDE'];
                $HASTA=$params_array['HASTA'];

                $sql=" SELECT DISTINCT EMP.NOMBRE_ESTACION,B.CAPACIDAD,PA.LATITUD,PA.LONGITUD,PA.VELOCIDAD,PA.FECHA,PA.HORA ,TR.NOM_TRP
                FROM Estacion_Marcacion_Prueba EMP
                INNER JOIN [192.168.2.210].[bsis_rem_afr].[dbo].Buses B on B.IdEmpresa=EMP.idempresa and B.COD_BUS=EMP.codbus AND B.COD_TRP=EMP.COD_TRP
                LEFT JOIN [192.168.60.25].[BUSES].[dbo].Posiciones_Android PA ON PA.IdEstacion=EMP.ID
                INNER JOIN [192.168.2.210].[bsis_rem_afr].[dbo].Empresa E on E.IdEmpresa=EMP.idempresa
                INNER JOIN [192.168.2.210].[erpfrusys].[dbo].TRANSPORTISTAS TR ON TR.COD_EMP=E.COD_EMP_CONT AND TR.COD_TRP=EMP.COD_TRP AND TR.COD_TEM='22'
                WHERE sw_activo='1' and PA.Fecha='{$FECHA}' and EMP.NOMBRE_ESTACION='{$ESTACION}'  and PA.HORA  BETWEEN '{$DESDE}' AND '{$HASTA}'
                ORDER BY HORA ";


                $marcaciones=DB::connection('sqlsrv5')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'datos encontrados',
                    'data'=>$marcaciones
                );

            }
        }

        return response()->json($data,$data['code']);
    }




}
