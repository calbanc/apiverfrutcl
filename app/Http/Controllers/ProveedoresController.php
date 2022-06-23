<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedores;

class ProveedoresController extends Controller
{

    public function index (Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Fallo en validacion de datos enviados'
                );
            }else{
                $COD_EMP=$params_array['COD_EMP'];
                $COD_TEM=$params_array['COD_TEM'];
                $proveedores=Proveedores::select('CodigoCliente','NombreCliente','RutCliente')
                            ->where('COD_EMP',$COD_EMP)
                            ->where('COD_TEM',$COD_TEM)
                            ->orderBy('NombreCliente')
                            ->get();
                if(!empty($proveedores)){
                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'proveedores'=>$proveedores
                    );
                }else{
                    $data=array(
                        'status'=>'error',
                        'code'=>400,
                        'message'=>'No hay proveedores en esa empresa y temporada'
                    );
                }
            }

        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Debe enviar una empresa y una temporada'
            );
        }

        return response()->json($data,$data['code']);
    }

}