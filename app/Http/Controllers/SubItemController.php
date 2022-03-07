<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subitem;

class SubItemController extends Controller
{
    //

    public function combustible(Request $request){
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
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Debe ingresar empresa y temporada'
                );
            }else{
                unset($params_array['DESCRIPCION']);
                $cod_emp=$params_array['COD_EMP'];
                $cod_tem=$params_array['COD_TEM'];
                $subitem=Subitem::select('SUBITEM','DES_SITEM')->where('COD_EMP',$cod_emp)
                ->where('COD_TEM',$cod_tem)
                ->where('SW_COMBUSTIBLE','1')
                ->orderBy('DES_SITEM')
                ->get();
    
                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'subitem'=>$subitem
                    );    
            }


        
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar una empresa'
            );
        }




       return response()->json($data,$data['code']);

    }
}
