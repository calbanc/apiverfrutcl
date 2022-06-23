<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Marcaciones;

class MarcacionesController extends Controller
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

    public function searchbyrutdate(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'FECHA'=>'required',
                'RUTTRABAJADOR'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'validando datos',
                    'error'=>$validate->errors()
                );
            }else{
                $sql="  SELECT DISTINCT m.RutTrabajador
                ,Empresa = ltrim(str(T.IdEmpresa)) + ' ' + E.Nombre,
                t.ApellidoPaterno,t.ApellidoMaterno,t.Nombre,m.fecha,CONVERT(varchar,m.hora,24) as Hora,m.Latitud,m.Longitud,est.NOMBRE_ESTACION,est.TIPO_ESTACION,c.IdCosto
                ,O.Descripcion AS Oficio,[Tipo Regimen]= tr.descripcion,c.IdZona,z.Nombre as 'Zona Labor',est.Id as 'Id Equipo Marcacion'

              ,left(CONVERT(varchar,m.hora,24),5) as Hora2
               From  [192.168.60.8\SQLEXPRESS].[Marcaciones].[dbo].Marcacion_Android M
                Inner Join Trabajador t WITH(NOLOCK) on T.RutTrabajador   =
                  CASE CHARINDEX('-', M.Ruttrabajador) WHEN 0 THEN CAST(M.Ruttrabajador AS INT) ELSE CAST(SUBSTRING(M.Ruttrabajador, 1, CHARINDEX('-', M.Ruttrabajador) - 1 ) AS INT) END

                inner join Contratos c WITH(NOLOCK) on c.IdEmpresa=T.IdEmpresa AND t.IdTrabajador=c.IdTrabajador
                       AND M.Fecha BETWEEN C.FECHAINICIO AND ISNULL(ISNULL(C.FECHATERMINO,C.FECHATERMINOC),GETDATE())
                LEFT join Zona z WITH(NOLOCK) on z.IdZona=c.IdZona AND z.IdEmpresa=c.IdEmpresa
                LEFT JOIN Oficio O WITH(NOLOCK) ON c.IdEmpresa=O.IdEmpresa AND c.IdOficio=O.IdOficio
                LEFT join TipoRegimen tr WITH(NOLOCK)  on tr.idtipo=C.idregimen
                LEFT outer join [192.168.60.8\SQLEXPRESS].[Marcaciones].[dbo].Estacion_Marcacion_Prueba est WITH(NOLOCK) on est.ID=M.IdEstacion
                INNER JOIN Empresa E WITH(NOLOCK) ON E.IdEmpresa= T.IdEmpresa
                LEFT JOIN TipoDctoIden TDI ON  TDI.idempresa=T.idempresa AND TDI.IdTipoDctoIden = T.IdTipoDctoIden
                where m.fecha='{$params_array['FECHA']}' AND m.RutTrabajador='{$params_array['RUTTRABAJADOR']}'";

                $marcaciones=DB::connection('sqlsrv4')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'datos encontrados',
                    'data'=>$marcaciones
                );


        }




        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe enviar datos'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function searchbyestaciondate(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'FECHA'=>'required',
                'ESTACION'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'validando datos',
                    'error'=>$validate->errors()
                );
            }else{
                $sql="  SELECT DISTINCT m.RutTrabajador
                ,Empresa = ltrim(str(T.IdEmpresa)) + ' ' + E.Nombre,
                t.ApellidoPaterno,t.ApellidoMaterno,t.Nombre,m.fecha,CONVERT(varchar,m.hora,24) as Hora,m.Latitud,m.Longitud,est.NOMBRE_ESTACION,est.TIPO_ESTACION,c.IdCosto
                ,O.Descripcion AS Oficio,[TipoRegimen]= tr.descripcion,c.IdZona,z.Nombre as 'ZonaLabor',est.Id as 'Id Equipo Marcacion'

              ,left(CONVERT(varchar,m.hora,24),5) as Hora2
               From  [192.168.60.8\SQLEXPRESS].[Marcaciones].[dbo].Marcacion_Android M
                Inner Join Trabajador t WITH(NOLOCK) on T.RutTrabajador   =
                  CASE CHARINDEX('-', M.Ruttrabajador) WHEN 0 THEN CAST(M.Ruttrabajador AS INT) ELSE CAST(SUBSTRING(M.Ruttrabajador, 1, CHARINDEX('-', M.Ruttrabajador) - 1 ) AS INT) END

                inner join Contratos c WITH(NOLOCK) on c.IdEmpresa=T.IdEmpresa AND t.IdTrabajador=c.IdTrabajador
                       AND M.Fecha BETWEEN C.FECHAINICIO AND ISNULL(ISNULL(C.FECHATERMINO,C.FECHATERMINOC),GETDATE())
                LEFT join Zona z WITH(NOLOCK) on z.IdZona=c.IdZona AND z.IdEmpresa=c.IdEmpresa
                LEFT JOIN Oficio O WITH(NOLOCK) ON c.IdEmpresa=O.IdEmpresa AND c.IdOficio=O.IdOficio
                LEFT join TipoRegimen tr WITH(NOLOCK)  on tr.idtipo=C.idregimen
                LEFT outer join [192.168.60.8\SQLEXPRESS].[Marcaciones].[dbo].Estacion_Marcacion_Prueba est WITH(NOLOCK) on est.ID=M.IdEstacion
                INNER JOIN Empresa E WITH(NOLOCK) ON E.IdEmpresa= T.IdEmpresa
                LEFT JOIN TipoDctoIden TDI ON  TDI.idempresa=T.idempresa AND TDI.IdTipoDctoIden = T.IdTipoDctoIden
                where m.fecha='{$params_array['FECHA']}' AND est.NOMBRE_ESTACION='{$params_array['ESTACION']}'
                ORDER BY Hora asc";

                $marcaciones=DB::connection('sqlsrv4')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'datos encontrados',
                    'data'=>$marcaciones
                );


        }




        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe enviar datos'
            );
        }

        return response()->json($data,$data['code']);
    }


}
