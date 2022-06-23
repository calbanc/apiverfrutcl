<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstacionMarcacion;


class EstacionMarcacionController extends Controller
{
    //
    public function index(){
        $estaciones=EstacionMarcacion::where('IDTEMPORADA',"22")

                                        ->orderBy('NOMBRE_ESTACION')
                                        ->get();

            $data=array(
                'status'=>'ok',
                'code'=>200,
                'estacion'=>$estaciones
            );


       return response()->json($data,$data['code']);

    }


}
