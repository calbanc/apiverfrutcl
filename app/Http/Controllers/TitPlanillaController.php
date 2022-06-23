<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TitPlanilla;
use App\Models\DetallePlanilla;
use Illuminate\Support\Facades\DB;

class TitPlanillaController extends Controller
{
    //

    public function index(){

        $planillas=TitPlanilla::all();

        $data=array(
            'status' =>'ok',
            'code'=>200,
            'planillas'=>$planillas
        );

        return response()->json($data,$data['code']);
    }
    public function listemptemp(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'IDTEMPORADA'=>'required',
            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos'
                );
            }else{
                $IDEMPRESA=$params_array['IDEMPRESA'];
                $IDTEMPORADA=$params_array['IDTEMPORADA'];

                 $sql="SELECT TP.IDEMPRESA,TP.IDTEMPORADA,TP.IDPLANILLA,TP.DESCRIPCION,COUNT(TD.IDPLANILLA) AS CANTIDAD
                  FROM TIT_PLANILLA TP
                  LEFT JOIN TIT_DATOS TD ON TP.IDEMPRESA=TD.IDEMPRESA AND TP.IDTEMPORADA=TD.IDTEMPORADA AND TP.IDPLANILLA=TD.IDREGISTRO
                  WHERE TP.IDEMPRESA='{$IDEMPRESA}' AND TP.IDTEMPORADA='{$IDTEMPORADA}'
                  GROUP BY TP.IDEMPRESA,TP.IDTEMPORADA,TP.IDPLANILLA,TP.DESCRIPCION,TD.IDPLANILLA";

                 $planillas=DB::connection('sqlsrv6')->select($sql);
                 if(!empty($planillas)){
                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'planillas'=>$planillas
                    );
                }else{
                    $data=array(
                        'status'=>'error',
                        'code'=>404,
                        'message'=>'Sin planillas registradas'
                    );

                }
            }

        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe enviar empresa y temporada'
            );
        }
        return response()->json($data,$data['code']);
    }

    public function show($id){
        $planilla=TitPlanilla::find($id);

        if(is_object($planilla)){
            $data=array(
                'status' =>'ok',
                'code'=>200,
                'planilla'=>$planilla
            );
        }else{
            $data=array(
                'status' =>'error',
                'code'=>400,
                'message' =>'No existe la planilla'
            );
        }

        return response()->json($data,$data['code']);
    }


    public function create(Request $request){

        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'IDTEMPORADA'=>'required',
                'DESCRIPCION'=>'required'
            ]);

            unset($params_array['IDPLANILLA']);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'error'=>$validate->errors()
                );
            }else{


                $id=TitPlanilla::select('IDPLANILLA')
                ->where('IDEMPRESA',$params_array['IDEMPRESA'])
                ->where('IDTEMPORADA',$params_array['IDTEMPORADA'])
                ->max('IDPLANILLA');

                if(empty($id)){
                    $id="1";
                }else{
                    $id++;
                }



                $planilla=new TitPlanilla();
                $planilla->IDEMPRESA=$params_array['IDEMPRESA'];
                $planilla->IDTEMPORADA=$params_array['IDTEMPORADA'];
                $planilla->IDPLANILLA=$id;
                $planilla->DESCRIPCION=$params_array['DESCRIPCION'];

                $planilla->save();
                $planillacreada=array(
                    'ID'=>$id,
                    'DESCRIPCION'=>$params_array['DESCRIPCION']
                );

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Planilla creada correctamente',
                    'planilla'=>$planillacreada
                );
            }

        }else{

            $data=array(
                'status'=>'error',
                'code' =>400,
                'message' =>'Debe ingresar datos'
            );


        }

        return response()->json($data,$data['code']);



    }
    public function destroy(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);


        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'IDTEMPORADA'=>'required',
                'IDPLANILLA'=>'required'

            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'error'=>$validate->errors()
                );
            }else{

                $detalle=DetallePlanilla::where('IDEMPRESA',$params_array['IDEMPRESA'])
                ->where('IDTEMPORADA',$params_array['IDTEMPORADA'])
                ->where('IDPLANILLA',$params_array['IDPLANILLA'])
                ->delete();

                $planilla=TitPlanilla::where('IDEMPRESA',$params_array['IDEMPRESA'])
                ->where('IDTEMPORADA',$params_array['IDTEMPORADA'])
                ->where('IDPLANILLA',$params_array['IDPLANILLA'])
                ->delete();
                if(!empty($planilla)){
                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'message'=>'Registro de planilla eliminado correctamente'
                    );
                }else{
                    $data=array(
                        'status'=>'error',
                        'code'=>400,
                        'message'=>'Error eliminando la planilla'
                    );
                }
            }
        }else{
            $data=array(
                'status'=>'error',
                'code' =>400,
                'message' =>'Debe ingresar datos'
            );
        }



        return response()->json($data,$data['code']);
    }

   /*  public function update(Request $request,$id){

        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'NOMBREAPP'=>'required|alpha',
                'DESCRIPCION'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos a actualizar',
                    'errors'=>$validate->errors()
                );
            }else{

                $app_update=App::where('ID',$id)->update($params_array);
                $data=array(
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>'Aplicacion actualizada',
                    'app'=>$params_array
                );
            }



        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar un id de Aplicacion'
            );
        }

        return response()->json($data,$data['code']);




    } */




}
