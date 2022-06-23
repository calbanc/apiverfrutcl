<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetallePlanilla;
use Illuminate\Support\Facades\DB;

class DetallePlanillaController extends Controller
{
    //

    public function index(){

        $item=Item::all();

        $data=array(
            'status' =>'ok',
            'code'=>200,
            'item'=>$item
        );

        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){

            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'IDTEMPORADA'=>'required',
                'IDPLANILLA'=>'required',
                'IDITEM'=>'required',
                'DESCRIPCION'=>'required'
            ]);



            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error en Validacion de datos',
                    'error'=>$validate->errors()
                );
            }else{


                $id=DetallePlanilla::select('IDORDEN')
                ->where('IDEMPRESA',$params_array['IDEMPRESA'])
                ->where('IDTEMPORADA',$params_array['IDTEMPORADA'])
                ->where('IDPLANILLA',$params_array['IDPLANILLA'])
                ->max('IDORDEN');

                if(empty($id)){
                    $id="1";
                }else{
                    $id++;
                }

                $detalleplanilla=new DetallePlanilla();
                $detalleplanilla->IDEMPRESA=$params_array['IDEMPRESA'];
                $detalleplanilla->IDTEMPORADA=$params_array['IDTEMPORADA'];
                $detalleplanilla->IDPLANILLA=$params_array['IDPLANILLA'];
                $detalleplanilla->IDORDEN=$id;
                $detalleplanilla->IDITEM=$params_array['IDITEM'];
                $detalleplanilla->DESCRIPCION=$params_array['DESCRIPCION'];

                $detalleplanilla->save();

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Detalle creado correctamente'
                );
            }
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar datos'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function delete(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);
        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'IDTEMPORADA'=>'required',
                'IDPLANILLA'=>'required',
                'IDORDEN'=>'required',

            ]);
            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error en Validacion de datos',
                    'error'=>$validate->errors()
                );
            }else{
                $detalle=DetallePlanilla::where('IDEMPRESA',$params_array['IDEMPRESA'])
                ->where('IDTEMPORADA',$params_array['IDTEMPORADA'])
                ->where('IDPLANILLA',$params_array['IDPLANILLA'])
                ->where('IDORDEN',$params_array['IDORDEN'])
                ->delete();

                if(!empty($detalle)){
                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'message'=>'Registro de detalle planilla eliminado correctamente'
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
                'code'=>400,
                'message'=>'Debe ingresar datos'
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
                'IDPLANILLA'=>'required'
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
                $planillas=DetallePlanilla::select('Detalle_Planilla.IDEMPRESA','Detalle_Planilla.IDTEMPORADA','Detalle_Planilla.IDPLANILLA'
                                                    ,'Detalle_Planilla.IDORDEN','Detalle_Planilla.IDITEM','ITEM.DESCRIPCION as ITEM_DESCRIPCION','Detalle_Planilla.DESCRIPCION' )
                                                    ->join('ITEM','ITEM.ID','=','Detalle_Planilla.IDITEM')
                                                    ->where('Detalle_planilla.IDEMPRESA',$params_array['IDEMPRESA'])
                                                    ->where('Detalle_planilla.IDTEMPORADA',$params_array['IDTEMPORADA'])
                                                    ->where('Detalle_planilla.IDPLANILLA',$params_array['IDPLANILLA'])
                                                    ->get();

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
