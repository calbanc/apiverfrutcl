<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Temporada;

class ProduccionController extends Controller
{
    //


    public function allempresas(){
        $data=Empresa::select('COD_EMP','NOM_EMP')
                        ->where('sw_despliegue_web','1')
                        ->get();

        $data=array(
            'status'=>'ok',
            'code'=>200,
            'data'=>$data
        );

        return response()->json($data,$data['code']);
    }
    public function temporadabyempresa(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'error'=>$validate->errors()
                );
            }else{
                $data=Temporada::select('COD_TEM','DESCRIPCION')
                                        ->where('COD_EMP',$params_array['COD_EMP'])
                                        ->orderBy('COD_TEM','ASC')
                                        ->get();
                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'data'=>$data

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
    public function obtenerGrupoProductoresByTemporadasAndEmpresa(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'FECHAINICIO'=>'required',
                'FECHAFIN'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'errors'=>$validate->errors()
                );
            }else{
                $fechainicio=$params_array['FECHAINICIO'];
                $fechafin=$params_array['FECHAFIN'];
                $sqlWhere = "";
                if($fechainicio != 'TODOS' && $fechafin != 'TODOS'){
                    $fechainicio .= "T00:00:00";
                    $fechafin .= "T00:00:00";
                    $sqlWhere.= " AND [Fecha_Proceso] BETWEEN '".$fechainicio."' and '".$fechafin."'";
                }

                $sql= "SELECT   Distinct (Nombre_Grupo_Productores) as grupoproductor
                FROM erpFrusys.dbo.viewConsultaPackingMasFrioExcelBasicoAndroid
                WHERE (Temporada ='".$params_array['COD_TEM'] ."') AND (Empresa='".$params_array['COD_EMP'] ."')".$sqlWhere;

                $data=DB::connection('sqlsrv3')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'data'=>$data
                );

            }
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe enviar parametros'
            );
        }
        return response()->json($data,$data['code']);
    }

    public function obtenerProductoresByTemporadasAndEmpresa(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'FECHAINICIO'=>'required',
                'FECHAFIN'=>'required',
                'GRUPO'=>'required'
            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'errors'=>$validate->errors()
                );
            }else{
                if($params_array['FECHAINICIO']  != 'TODOS' && $params_array['FECHAFIN']  != 'TODOS'){
                    $fechainicio .= "T00:00:00";
                    $fechafin .= "T00:00:00";
                    $sqlWhere.= " AND [Fecha_Proceso] BETWEEN '".$params_array['FECHAINICIO']."' and '".$params_array['FECHAFIN']."'";

                }
                $sqlWhere = "";
                $sqlWhere.=" and Nombre_Grupo_Productores='".$params_array['GRUPO'] ."'";
                $sql= "SELECT  Distinct(NombreProductor) as productor
                FROM erpFrusys.dbo.viewConsultaPackingMasFrioExcelBasicoAndroid
                WHERE (Temporada ='".$params_array['COD_TEM'] ."') AND (Empresa='".$params_array['COD_EMP'] ."')".$sqlWhere;

                $data=DB::connection('sqlsrv3')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'data'=>$data
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

    public function obtenerZonasByProductorTemporadasAndEmpresa(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'FECHAINICIO'=>'required',
                'FECHAFIN'=>'required',
                'PRODUCTOR'=>'required'
            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'errors'=>$validate->errors()
                );
            }else{
                $sqlWhere = "";
                if($params_array['FECHAINICIO']  != 'TODOS' && $params_array['FECHAFIN']  != 'TODOS'){
                    $fechainicio .= "T00:00:00";
                    $fechafin .= "T00:00:00";
                    $sqlWhere.= " AND [Fecha_Proceso] BETWEEN '".$params_array['FECHAINICIO']."' and '".$params_array['FECHAFIN']."'";

                }


                $sql= "SELECT  Distinct(Zona) as zona
                FROM erpFrusys.dbo.viewConsultaPackingMasFrioExcelBasicoAndroid
                WHERE (Temporada ='".$params_array['COD_TEM'] ."') AND (Empresa='".$params_array['COD_EMP'] ."') and (NombreProductor)='".$params_array['PRODUCTOR']."' ".$sqlWhere;

                $data=DB::connection('sqlsrv3')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'data'=>$data
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

    public function obtenerReportePorEspecie(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'FECHAINICIO'=>'required',
                'FECHAFIN'=>'required',
                'PRODUCTOR'=>'required',
                'ZONA'=>'required'
            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'errors'=>$validate->errors()
                );
            }else{
                $sqlWhere = "";
                if($params_array['FECHAINICIO']  != 'TODOS' && $params_array['FECHAFIN']  != 'TODOS'){
                    $fechainicio .= "T00:00:00";
                    $fechafin .= "T00:00:00";
                    $sqlWhere.= " AND [Fecha_Proceso] BETWEEN '".$params_array['FECHAINICIO']."' and '".$params_array['FECHAFIN']."'";

                }
                if($params_array['PRODUCTOR'] != 'TODOS'){
                    $sqlWhere .= " and [NombreProductor]='".$params_array['PRODUCTOR'] ."'";
                }
                if($params_array['ZONA']!='TODOS'){
                    $sqlWhere .= " and Zona = '".$params_array['ZONA']."'";
                }


                $sql= "SELECT Distinct (NombreEspecie),SUM (Cajas) AS Bulto,SUM (CajasEquivalentes) as Bases
                FROM erpFrusys.dbo.viewConsultaPackingMasFrioExcelBasicoAndroid
                WHERE (Temporada ='".$params_array['COD_TEM'] ."') AND (Empresa='".$params_array['COD_EMP'] ."')".$sqlWhere."Group by NombreEspecie ORDER BY NombreEspecie ASC";

                $data=DB::connection('sqlsrv3')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'data'=>$data
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

    public function obtenerReportePorVariedad(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'FECHAINICIO'=>'required',
                'FECHAFIN'=>'required',
                'PRODUCTOR'=>'required',
                'ZONA'=>'required',
                'ESPECIE'=>'required'
            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'errors'=>$validate->errors()
                );
            }else{
                $sqlWhere = "";
                if($params_array['FECHAINICIO']  != 'TODOS' && $params_array['FECHAFIN']  != 'TODOS'){
                    $fechainicio .= "T00:00:00";
                    $fechafin .= "T00:00:00";
                    $sqlWhere.= " AND [Fecha_Proceso] BETWEEN '".$params_array['FECHAINICIO']."' and '".$params_array['FECHAFIN']."'";

                }
                if($params_array['PRODUCTOR'] != 'TODOS'){
                    $sqlWhere .= " and [NombreProductor]='".$params_array['PRODUCTOR'] ."'";
                }
                if($params_array['ZONA']!='TODOS'){
                    $sqlWhere .= " and Zona = '".$params_array['ZONA']."'";
                }

                if($params_array['ESPECIE']!='TODOS'){
                    $sqlWhere .= " and NombreEspecie = '".$params_array['ESPECIE']."'";
                }


                $sql= " SELECT Distinct  NombreEspecie,NombreVariedad,SUM (Cajas) AS Bulto,SUM (CajasEquivalentes) as Bases
                FROM erpFrusys.dbo.viewConsultaPackingMasFrioExcelBasicoAndroid
                WHERE (Temporada ='".$params_array['COD_TEM'] ."') AND (Empresa='".$params_array['COD_EMP'] ."')".$sqlWhere." Group by NombreVariedad,NombreEspecie ORDER BY NombreVariedad ASC,NombreEspecie ASC";

                $data=DB::connection('sqlsrv3')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'data'=>$data
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

    public function obtenerReporteTotales(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'FECHAINICIO'=>'required',
                'FECHAFIN'=>'required',
                'PRODUCTOR'=>'required',
                'ZONA'=>'required',

            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'errors'=>$validate->errors()
                );
            }else{
                $sqlWhere = "";
                if($params_array['FECHAINICIO']  != 'TODOS' && $params_array['FECHAFIN']  != 'TODOS'){
                    $fechainicio .= "T00:00:00";
                    $fechafin .= "T00:00:00";
                    $sqlWhere.= " AND [Fecha_Proceso] BETWEEN '".$params_array['FECHAINICIO']."' and '".$params_array['FECHAFIN']."'";

                }
                if($params_array['PRODUCTOR'] != 'TODOS'){
                    $sqlWhere .= " and [NombreProductor]='".$params_array['PRODUCTOR'] ."'";
                }
                if($params_array['ZONA']!='TODOS'){
                    $sqlWhere .= " and Zona = '".$params_array['ZONA']."'";
                }



                $sql= " SELECT Empresa as Total, sum(cajas)as Bulto,SUM (CajasEquivalentes) as Bases
                FROM erpFrusys.dbo.viewConsultaPackingMasFrioExcelBasicoAndroid
                WHERE (Temporada ='".$params_array['COD_TEM'] ."') AND (Empresa='".$params_array['COD_EMP'] ."')".$sqlWhere." Group by Empresa";

                $data=DB::connection('sqlsrv3')->select($sql);

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'data'=>$data
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
