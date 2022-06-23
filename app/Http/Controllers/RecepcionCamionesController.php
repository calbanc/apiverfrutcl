<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecepcionCamiones;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;


class RecepcionCamionesController extends Controller
{
    //


    public function index(){
        $aplicacion=RecepcionCamiones::all();

        $data=array(
            'status'=>'ok',
            'code'=>200,
            'Camiones'=>$aplicacion
        );

        return response()->json($data,$data['code']);
    }

    public function listbyemp(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);
        $token=$request->header('Auth');
        $jwtAuth=new \JwtAuth();
        $checktoken=$jwtAuth->checkToken($token);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'error'=>$validate->errors()
                );
            }else{
                $info=array("Database"=>"erpfrusys","UID"=>'reporte',"PWD"=>'abc.123456',"CharacterSet"=>"UTF-8");
                $hostname_localhost="192.168.2.210";
                $conexion = sqlsrv_connect($hostname_localhost,$info);


                $COD_EMP=$params_array['COD_EMP'];

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

                $ZON=$params_array['ZON'];
                $FECHA=$params_array['FECHA'];
                $TIPO=$params_array['TIPO'];

                $sqlwhere="";
                if($ZON<>'TODOS'){
                    $sqlwhere.="AND ZON='{$ZON}'";
                }
                if($FECHA<>'TODOS'){
                    $sqlwhere.="AND CONVERT(varchar,Fecha_ingreso,103)='{$FECHA}'";
                }

                if($TIPO<>'TODOS'){
                    $sqlwhere.="AND TIPO='{$TIPO}'";
                }
                $order="ORDER BY ID DESC";

                $sql="SELECT * FROM ANDROID_RECEPCION_CAMIONES WHERE COD_EMP='{$COD_EMP}' AND COD_TEM='{$params_array['COD_TEM']}' " .$sqlwhere ." ORDER BY ID DESC";


                $camiones=DB::connection('sqlsrv3')->select($sql);
                if(!empty($camiones)){
                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'camiones'=>$camiones
                    );
                }else{
                    $data=array(
                        'status'=>'error',
                        'code'=>400,
                        'message'=>'No hay camiones registrados'
                    );
                }
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

    public function listbyempzon(Request $request){
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);
        $token=$request->header('Auth');
        $jwtAuth=new \JwtAuth();
        $checktoken=$jwtAuth->checkToken($token);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'ZON'=>'required'
            ]);



            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error en validacion de datos',
                    'errors'=>$validate->errors()
                );
            }else{
                $camiones=RecepcionCamiones::where('COD_EMP',$params_array['COD_EMP'])
                                            ->where('COD_TEM',$params_array['COD_TEM'])
                                            ->where('ZON',$params_array['ZON'])
                                            ->orderBy('Id','desc')
                                            ->get();
                if(!empty($camiones)){
                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'camiones'=>$camiones
                    );
                }else{
                    $data=array(
                        'status'=>'error',
                        'code'=>400,
                        'message'=>'Camiones no encontrados'
                    );
                }
            }

        }else{
            $data=array(
                'status'=>'ok',
                'code'=>400,
                'message'=>'Error debe enviar datos'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function create(Request $request){
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);
        $params=json_decode($json);


        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'RUT'=>'required',
                'NOMBRE'=>'required',
                'LUGARPROCEDENCIA'=>'required',
                'TELEFONO'=>'required',
                'DIRIGE'=>'required',
                'MOTIVO'=>'required',
                'AUTORIZADO'=>'required',


            ]);


            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'errorz'=>$validate->errors()
                );

            }else{
                $fecharegistro=date("d-m-Y",time());
                $visita=RecepcionCamiones::SELECT('Id')
                ->where('SW_WEB',true)
                ->where('RutConductor',$params_array['RUT'])
                ->whereDate('FECHA_REGISTRO',$fecharegistro)
                ->where('Fecha_salida',null)
                ->where('TIPO','VISITA')
                ->orderBy('Id','desc')
                ->get();

                $cantidad=count($visita);



                 if($cantidad===0){
                    $fecharegistro=date("d-m-Y H:i:s",time());
                    $camion=new RecepcionCamiones();
                    $camion->RutConductor= strtoupper($params_array['RUT']);
                    $camion->NombreConductor=strtoupper($params_array['NOMBRE']) ;
                    $camion->PROCEDENCIA=strtoupper($params_array['LUGARPROCEDENCIA']) ;
                    $camion->Telefono=strtoupper($params_array['TELEFONO']);
                    $camion->Patente=strtoupper($params_array['PATENTE']);
                    $camion->DESTINO=strtoupper($params_array['DIRIGE']);
                    $camion->Motivo=strtoupper($params_array['MOTIVO']);
                    $camion->AUTORIZADO=strtoupper($params_array['AUTORIZADO']);
                    $camion->Observacion=strtoupper($params_array['OBSERVACION']);
                    $camion->NROLICENCIA=strtoupper($params_array['NROLICENCIA']);
                    $camion->SGROVIGENTE=strtoupper($params_array['SEGUROVIGENTE']);
                    if(!empty($params_array['FECHAINICIO'])){
                        $camion->FECHAINICIOSCTR=strtoupper($params_array['FECHAINICIO']);
                    }
                    if(!empty($params_array['FECHATERMINO'])){
                        $camion->FECHATERMINOSCTR=strtoupper($params_array['FECHATERMINO']);
                    }
                    $camion->BUENASALUD=strtoupper($params_array['BUENASALUD']);
                    $camion->OBSERVACIONSALUD=strtoupper($params_array['OBSERVACIONSALUD']);
                    $camion->TIPO="VISITA";
                    $camion->Guia_ingreso=strtoupper($params_array['NGUIA']);
                    $camion->SW_WEB=true;
                    $camion->FECHA_REGISTRO=$fecharegistro;
                    $camion->Sw_Despachado=false;
                    $camion->save();

                    $visita=RecepcionCamiones::SELECT('Id')
                    ->where('SW_WEB',true)
                    ->where('RutConductor',$params_array['RUT'])
                    ->whereDate('FECHA_REGISTRO',$fecharegistro)
                    ->where('TIPO','VISITA')
                    ->where('Fecha_salida',null)
                    ->orderBy('Id','desc')
                    ->get();

                    $data=array(
                        'code'=>200,
                        'status'=>'ok',
                        'message'=>'Registrado correctamente',
                        'data'=>$visita
                    );

                 }else{
                    $data=array(
                         'code'=>200,
                         'status'=>'ok',
                         'message'=>'Ya cuenta con un registro, garita debe marcar su salida para registrarse nuevamente',
                         'data'=>$visita
                     );
                 }
            }
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Error debe enviar datos'
            );
        }
        return response()->json($data,$data['code']);
    }

    public function searchbyrut(Request $request){
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'RUT'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'errors'=>$validate->error()
                );
            }else{
                $fecharegistro=date("d-m-Y",time());
                $visita=RecepcionCamiones::SELECT('Id')
                ->where('SW_WEB',true)
                ->where('RutConductor',$params_array['RUT'])
                ->whereDate('FECHA_REGISTRO',$fecharegistro)
                ->where('Fecha_salida',null)
                ->where('TIPO','VISITA')
                ->orderBy('Id','desc')
                ->get();

                $cantidad=count($visita);
                if($cantidad==0){
                    $visita=RecepcionCamiones::select('RutConductor','NombreConductor','Telefono','NROLICENCIA')
                                                ->where('RutConductor',$params_array['RUT'])
                                                ->where('TIPO','VISITA')
                                                ->orderby('Id','desc')
                                                ->first();
                    $data=array(
                        'code'=>200,
                        'status'=>'ok',
                        'message'=>'Sin codigo registrado',
                        'data'=>$visita
                    );
                }else{
                    $data=array(
                        'code'=>202,
                        'status'=>'ok',
                        'message'=>'Ya cuenta con un registro, garita debe marcar su salida para registrarse nuevamente',
                        'data'=>$visita
                    );
                }

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
