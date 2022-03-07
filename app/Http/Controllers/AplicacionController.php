<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aplicacion;

class AplicacionController extends Controller
{
    //


    public function index(){
        $aplicacion=Aplicacion::all();

        $data=array(
            'status'=>'ok',
            'code'=>200,
            'Aplicacion'=>$aplicacion
        );

        return response()->json($data,$data['code']);
    }
}
