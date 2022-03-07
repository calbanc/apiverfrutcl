<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trabajador;
use Illuminate\Support\Facades\DB;
class TrabajadorController extends Controller
{
    //

    public function index(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'IdEmpresa'=>'required',
                'Rut'=>'required|numeric'
            ]);

            if($validate->fails()) {
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error en validacion de datos',
                    'error'=>$validate->errors()
                );
            }else{
                $idempresa=$params_array['IdEmpresa'];
                $rut=$params_array["Rut"];
                $trabajador=Trabajador::select('IdTrabajador','Nombre', 'ApellidoPaterno','ApellidoMaterno')->where('IdEmpresa',$idempresa)
                ->where('RutTrabajador',$rut)
                ->get();

                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'trabajador'=>$trabajador
                    );
            }



        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar datos como empresa y rut del trabajador'
            );
        }




       return response()->json($data,$data['code']);

    }
    public function buscatrabajador(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            
            $validate=\Validator::make($params_array,[
                'IdEmpresa'=>'required',
                'Rut'=>'required|numeric',
                'IdTemporada'=>'required'
            ]);



            $idempresa=$params_array['IdEmpresa'];
            $rut=$params_array["Rut"];
            $temporada=$params_array["IdTemporada"];
            $sql="TRANS_RENDICIONES_CHOFERES @COD_EMP='$idempresa',@COD_TEM='$temporada', @IDTRABAJADOR='$rut',   @OPTION=5";
    
            $trabajador=DB::connection('sqlsrv3')->select($sql);

            if(!empty($trabajador)){
                $data=array(
                    'code'=>'200',
                    'status'=>'ok',
                    'trabajador'=>$trabajador
                );
            }else{
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Trabajador no encontrado'
                );
         
            }
            

        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Debe ingresar datos para continuar'
            );
        }

        return response()->json($data,$data['code']);
    }


    public function maquinatrabajador(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);
        if(!empty($params_array)){

           // $year = (new DateTime)->format("Y");
            //$month=(new DateTime)->format("m");
            $YEAR=date("Y");
            $MONTH=date("m");
           
        
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'SUBITEM'=>'required',
                'IDTRABAJADOR'=>'required'
                

            ]);

            $COD_EMP=$params_array['COD_EMP'];
            $COD_TEM=$params_array['COD_TEM'];
            $SUBITEM=$params_array['SUBITEM'];
            $IDTRABAJADOR=$params_array['IDTRABAJADOR'];


            $sql="TRANS_RENDICIONES_CHOFERES @COD_EMP='$COD_EMP',@COD_TEM='$COD_TEM', @IDTRABAJADOR='$IDTRABAJADOR', @SUBITEM='$SUBITEM',@ANO='$YEAR',@MES='$MONTH',  @OPTION=6   ";	
            $maquina=DB::connection('sqlsrv3')->select($sql);
         
           
            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Trabajador no tiene una maquina asignada'
                );
            }else{
                if(!empty($maquina)){
                    $data=array(
                        'code'=>'200',
                        'status'=>'ok',
                        'maquina'=>$maquina
                    );
                }else{
                    $data=array(
                        'code'=>400,
                        'status'=>'error',
                        'message'=>'Trabajador no tiene una maquina asignada'
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
