<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleDatos;
use Illuminate\Support\Facades\DB;

class DetalleDatosController extends Controller
{
    //

    public function index(){


    }

    public function listbyidplanilla(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);
        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'IDTEMPORADA'=>'required',
                'IDPLANILLA'=>'required']
            );
            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Error validando datos',
                    'errors'=>$validate->errors()
                );
            }else{
                $IDEMPRESA=$params_array['IDEMPRESA'];
                $IDTEMPORADA=$params_array['IDTEMPORADA'];
                $IDPLANILLA=$params_array['IDPLANILLA'];

                $sql="  SELECT td.IDREGISTRO,convert(varchar,td.FECHA,23) as 'Fecha',CONVERT(varchar,td.HORA,8) as 'Hora',[Usuario]=t.ApellidoPaterno+' '+t.ApellidoMaterno+' '+t.Nombre
                FROM TIT_DATOS TD
                INNER JOIN  [192.168.2.210].[bsis_rem_afr].dbo.trabajador t on t.usuariosis=td.USUARIO
                WHERE TD.IDEMPRESA='{$IDEMPRESA}' AND TD.IDTEMPORADA='{$IDTEMPORADA}' AND TD.IDPLANILLA='{$IDPLANILLA}'";
                $planillas=DB::connection('sqlsrv6')->select($sql);

                if(!empty($planillas)){
                    $data=array(
                        'code'=>200,
                        'status'=>'ok',
                        'message'=>'planilla encontrada',
                        'planillas'=>$planillas
                    );
                }else{
                    $data=array(
                        'code'=>404,
                        'status'=>'error',
                        'message'=>'planilla no encontrada'
                    );
                }
            }
        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Debe enviar datos'
            );
        }
        return response()->json($data,$data['code']);
    }


    public function detail(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);
        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'IDTEMPORADA'=>'required',
                'IDPLANILLA'=>'required',
                'IDREGISTRO'=>'required'
                ]
            );
            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Error validando datos',
                    'errors'=>$validate->errors()
                );
            }else{
                $IDEMPRESA=$params_array['IDEMPRESA'];
                $IDTEMPORADA=$params_array['IDTEMPORADA'];
                $IDPLANILLA=$params_array['IDPLANILLA'];
                $IDREGISTRO=$params_array['IDREGISTRO'];
                $sql="SELECT DP.IDEMPRESA,DP.IDTEMPORADA,DP.IDPLANILLA,DP.IDORDEN,DP.IDITEM,DP.DESCRIPCION AS 'TITULO',
                ISNULL(DD.DESCRIPCION,'') AS 'DATO',IT.COMANDO,IT.DESCRIPCION,DD.IDREGISTRO
                FROM DETALLE_PLANILLA DP
                INNER JOIN ITEM IT ON IT.ID=DP.IDITEM
                LEFT JOIN TIT_DATOS TD ON TD.IDEMPRESA=DP.IDEMPRESA AND TD.IDPLANILLA=DP.IDPLANILLA AND TD.IDTEMPORADA=DP.IDTEMPORADA
                LEFT JOIN DETALLE_DATOS DD ON DD.IDEMPRESA=DP.IDEMPRESA AND DD.IDTEMPORADA=DP.IDTEMPORADA AND DD.IDREGISTRO=TD.IDREGISTRO AND DD.IDORDEN=DP.IDORDEN
                WHERE DP.IDEMPRESA='{$IDEMPRESA}' AND DP.IDTEMPORADA='{$IDTEMPORADA}' AND DP.IDPLANILLA='{$IDPLANILLA}' AND TD.IDREGISTRO='{$IDREGISTRO}'";
                $planillas=DB::connection('sqlsrv6')->select($sql);

                if(!empty($planillas)){
                    $data=array(
                        'code'=>200,
                        'status'=>'ok',
                        'message'=>'planilla encontrada',
                        'planillas'=>$planillas
                    );
                }else{
                    $data=array(
                        'code'=>404,
                        'status'=>'error',
                        'message'=>'planilla no encontrada'
                    );
                }
            }
        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Debe enviar datos'
            );
        }
        return response()->json($data,$data['code']);
    }

}
