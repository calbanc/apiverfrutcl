<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Temporada;
use App\Models\TemporadaRemuneraciones;
use Illuminate\Support\Facades\DB;
class EmpresaController extends Controller
{
    //

    public function index(Request $request){

        $token=$request->header('Auth');
        $jwtAuth=new \JwtAuth();
        $checktoken=$jwtAuth->checkToken($token);

        if($checktoken){
            $user=$jwtAuth->checkToken($token,true);
            $sql="SELECT distinct
            [NOM_EMP]=ISNULL(E.NOM_EMP,ER.NOMBRE),
            [COD_EMP]=P.IDEMPRESA
            FROM BPRIV.DBO.PERFIL P
            LEFT JOIN ERPFRUSYS.DBO.EMPRESAS E ON P.IDEMPRESA = E.COD_EMP
            LEFT JOIN BSIS_REM_AFR.DBO.EMPRESA ER ON P.IDEMPRESA = LTRIM(STR(ER.IDEMPRESA))
            LEFT JOIN  BPRIV.DBO.PROTOCOLOS_USUARIOS PRO ON PRO.IDUSUARIO=P.IDUSUARIO

            WHERE PRO.IDUSUARIO='$user->IDUSUARIO' AND P.IDAPLICACION LIKE ('APP%') order by cod_emp , NOM_EMP";
            $empresa=DB::connection('sqlsrv3')->select($sql);
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'empresa'=>$empresa
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Error en el token'
            );
        }



        /* $empresa=Empresa::select('COD_EMP', 'NOM_EMP')
       // ->where('SW_DESPLIEGUE_WEB','1')
        ->get();
        $data=array(
            'status'=>'ok',
            'code'=>200,
            'empresa'=>$empresa
        ); */




       return response()->json($data,$data['code']);

    }

    public function temporada(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Debe ingresar una empresa'
                );
            }else{
                $cod_emp=$params_array['COD_EMP'];

                if(is_numeric($cod_emp)){
                    //$temporada=TemporadaRemuneraciones::select(['IdTemporada AS COD_TEM'],['Descripcion'])
                    $temporada=TemporadaRemuneraciones::select('IdTemporada as COD_TEM','Descripcion AS DESCRIPCION')
                                                        ->where('IDEMPRESA',$cod_emp)
                                                        ->orderBy('IdTemporada','DESC')
                                                        ->get();
                }else{
                    $temporada=Temporada::select('COD_TEM','DESCRIPCION')
                    -> where('COD_EMP',$cod_emp)
                    ->orderBy('ORDEN','DESC')
                     ->get();

                }

                $data=array(
                    'code'=>200,
                    'status'=>'ok',
                    'temporada'=>$temporada) ;

            }

        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'debe enviar valores'
            );
        }

        return response()->json($data,$data['code']);
    }




}
