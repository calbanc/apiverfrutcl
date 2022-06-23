<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rendimiento;
use Illuminate\Support\Facades\DB;
class RendimientoController extends Controller
{
    
    public function store(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'IDZONA'=>'required',
                'IDCUARTEL'=>'required',
                'CODLINEA'=>'required',
                'ID'=>'required'
            ]);
            if($validate->fails()){
                $data=array(
                    'code'=>'404',
                    'status'=>'error',
                    'message'=>'Error en validacion de datos enviados',
                    'error'=>$validate->errors()
                );
            }else{
                date_default_timezone_set('America/Lima');
                $fecha=date('d/m/Y',time());
                $hora=date('H:i:s',time());
                $COD_EMP=$params_array['COD_EMP'];
                $COD_TEM=$params_array['COD_TEM'];
                $IDZONA=$params_array['IDZONA'];
                $CODLINEA=$params_array['CODLINEA'];
                $IDCUARTEL=$params_array["IDCUARTEL"];
                $ID=$params_array['ID'];
                $division = explode("-", $ID);
                $IDFAMILIA=$division[0];
                $IDACTIVIDAD=$division[1];
                $RutTrabajador=$division[2];
                $rendimiento=new Rendimiento();
                $rendimiento->Id=$ID;
                $rendimiento->RutTrabajador=$RutTrabajador;
                $rendimiento->IdEmpresa=$COD_EMP;
                $rendimiento->IdTemporada=$COD_TEM;
                $rendimiento->IdFamilia=$IDFAMILIA;
                $rendimiento->IdActividad=$IDACTIVIDAD;
                $rendimiento->IdZona=$IDZONA;
                $rendimiento->IdCuartel=$IDCUARTEL;
                $rendimiento->Cod_linea=$CODLINEA;
                $rendimiento->Fecha=$fecha;
                $rendimiento->Hora=$hora;
                $rendimiento->save();
                $data=array(
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>'Registrado correctamente'
                );
                              
            }

        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Debe enviar datos '

            );
        }

        return response()->json($data,$data['code']);
    }

    public function top(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'IDZONA'=>'required',
                'IDCUARTEL'=>'required',
                'CODLINEA'=>'required',
            
            ]);
            if($validate->fails()){
                $data=array(
                    'code'=>'404',
                    'status'=>'error',
                    'message'=>'Error en validacion de datos enviados',
                    'error'=>$validate->errors()
                );
            }else{
                date_default_timezone_set('America/Lima');
                $fecha=date('d/m/Y',time());
                $hora=date('H:i:s',time());
                $COD_EMP=$params_array['COD_EMP'];
                $COD_TEM=$params_array['COD_TEM'];
                $IDZONA=$params_array['IDZONA'];
                $CODLINEA=$params_array['CODLINEA'];
                $IDCUARTEL=$params_array["IDCUARTEL"];
             
                $sql="SELECT  [Trabajador]=T.Nombre+' '+T.ApellidoPaterno+' '+T.ApellidoMaterno,[Labor]=act.Nombre,[Cantidad]=COUNT(R.RutTrabajador)
                from Rendimiento_Planta R
                Inner Join [192.168.2.210].[bsis_rem_afr].[dbo].Trabajador t WITH(NOLOCK) on T.RutTrabajador   = R.RutTrabajador  and T.IdEmpresa=R.IdEmpresa
                Inner Join [192.168.2.210].[bsis_rem_afr].[dbo].Actividades act WITH(NOLOCK) on act.IdEmpresa=R.IdEmpresa and act.IdFamilia=R.IdFamilia and act.IdActividad=R.IdActividad
                where R.IdEmpresa='$COD_EMP' and R.IdTemporada='$COD_TEM' and R.IdZona='$IDZONA' and R.IdCuartel='$IDCUARTEL'  and R.Cod_linea='$CODLINEA' and R.Fecha='$fecha'
                group by T.Nombre,T.ApellidoPaterno,T.ApellidoMaterno,R.RutTrabajador,act.Nombre
                order by Cantidad desc ";


                $rendimiento=DB::connection('sqlsrv5')->select($sql);
                
                $data=array(
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>'Top 10 de rendimientos',
                    'rendimiento'=>$rendimiento
                );
                              
            }

        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Debe enviar datos '

            );
        }

        return response()->json($data,$data['code']);
    }

}
