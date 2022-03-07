<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PROTOCOLOS_USUARIOS;

class ProtocolosUsuarioController extends Controller
{
    //

    public function usuario(Request $request){

        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);


       $usuarios=PROTOCOLOS_USUARIOS::all();
       return response()->json([
        'code'=>200,
        'status'=>'succes',
        'post'=>$usuarios
    ]);

   



}
