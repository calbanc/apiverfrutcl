<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
class RondinController extends Controller
{
    //


    public function index(){
        $aplicacion=Aplicacion::all();

        $data=array(
            'status'=>'ok',
            'code'=>200,
            'Aplicacion'=>$aplicacion
        );

        return response()->json($data,$data['code']);
    }
    public function allbyday(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'DESDE'=>'required',
                'HASTA'=>'required',
                'IDTRABAJADOR'=>'required'

            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'validando datos',
                    'error'=>$validate->errors()
                );
            }else{
                $IDEMPRESA=$params_array['IDEMPRESA'];
                $DESDE=$params_array['DESDE'];
                $HASTA=$params_array['HASTA'];
                $IDTRABAJADOR=$params_array['IDTRABAJADOR'];
                $SQLWHERE="";
                if($IDTRABAJADOR<>'TODOS'){
                    $SQLWHERE="AND T.IDTRABAJADOR='{$IDTRABAJADOR}' ";
                }
                $sql=" SELECT [Id Empresa]=A.IDEMPRESA, [Codigo Trab]=A.IDTRABAJADOR, [NombreTrab]=T.NOMBRE+' '+T.APELLIDOPATERNO+' '+T.APELLIDOMATERNO,
                [Fecha]=CONVERT(VARCHAR(10), A.FECHA, 105) , [Hora]=CONVERT(varchar,A.hora,8) , [ID Predio]=A.IDPREDIO, [Predio]=Z.NOMBRE , [ID Cuartel]=A.IDCUARTEL, [Cuartel]=C.NOMBRE ,
                 [Cumple]=(CASE WHEN A.SW_CUMPLE=1 THEN 'Si' ELSE 'No' END), [Inicio]=(CASE WHEN A.SW_INICIO=1 THEN 'Si' ELSE 'No' END),
                [Observacion]=A.OBSERVACION, [Latitud]=A.LATITUD, [Longuitud]=A.LONGITUD
                ,Mes=month(a.fecha) ,Año=year(a.fecha), Dia=day(a.fecha),[Cumplio Ruta]=(CASE WHEN A.swruta=1 THEN 'Si' ELSE 'No'END)
                FROM ANDROID_RONDIN A WITH(NOLOCK)
                LEFT JOIN Trabajador T  WITH(NOLOCK) ON T.IDEMPRESA =A.IdEmpresaTrabajador AND T.IDTRABAJADOR=A.IDTRABAJADOR
                INNER JOIN ZONA Z WITH(NOLOCK) ON Z.IDZONA=A.IDPREDIO AND Z.IDEMPRESA=A.IDEMPRESA
                INNER JOIN CUARTEL C WITH(NOLOCK) ON C.IDEMPRESA=A.IDEMPRESA AND C.IDCUARTEL=A.IDCUARTEL AND C.IDZONA=A.IdPredio
                INNER JOIN EMPRESA E WITH(NOLOCK) ON E.IDEMPRESA=A.IDEMPRESA
                WHERE A.IDEMPRESA ={$IDEMPRESA}
                 AND (A.Fecha BETWEEN '{$DESDE}' AND  '{$HASTA}') ".$SQLWHERE;


                $rondines=DB::connection('sqlsrv4')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'datos encontrados',
                    'data'=>$rondines
                );

            }
        }

        return response()->json($data,$data['code']);
    }

    public function rondinbyday(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',

            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'validando datos',
                    'error'=>$validate->errors()
                );
            }else{
                $IDEMPRESA=$params_array['IDEMPRESA'];
                $DESDE=$params_array['DESDE'];
                $HASTA=$params_array['HASTA'];
                $sql="SPC_ANDROID_RONDIN @OPCION=6 ,@IDEMPRESA='{$IDEMPRESA}'";

                $rondines=DB::connection('sqlsrv4')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'datos encontrados',
                    'data'=>$rondines
                );

            }
        }

        return response()->json($data,$data['code']);
    }

}
