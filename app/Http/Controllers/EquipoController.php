<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Equipo;
class EquipoController extends Controller
{
    //


    public function index(){
       /*   $sql="SELECT EQ.*,E.NOM_EMP
        FROM EQUIPO EQ
        INNER JOIN [192.168.2.210].[erpfrusys].[dbo].Empresas E on E.COD_EMP=EQ.IDEMPRESA";

        $equipo=DB::connection('sqlsrv')->select($sql);  */
        $equipo=Equipo::all();

        $data=array(
            'status'=>'ok',
            'code'=>200,
            'equipo'=>$equipo
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
                'IDZONA'=>'required',
                'OBSERVACIO'=>'required',
                'ID_USUARIO'=>'required',
                'NOM_ZON'=>'required',
                'NOM_EMP'=>'required',

            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'errors'=>$validate->errors()
                );
            }else{
                $equipo=new Equipo();
                $equipo->IDEMPRESA=$params_array['IDEMPRESA'];
                $equipo->IDZONA=$params_array['IDZONA'];
                if($params_array['IDEMPRESATRABAJADOR']!='NULL'){
                    $equipo->IDEMPRESATRABAJADOR=$params_array['IDEMPRESATRABAJADOR'];
                    $equipo->NOM_EMP_TRAB=$params_array['NOM_EMP_TRAB'];
                }
                if($params_array['IDTRABAJADOR']!='NULL'){
                    $equipo->IDTRABAJADOR=$params_array['IDTRABAJADOR'];
                    $equipo->NOM_TRAB=$params_array['NOM_TRAB'];
                }
                if($params_array['VPE']!='NULL'){
                    $equipo->VPE=$params_array['VPE'];
                }
                if($params_array['ID_NOTEBOOK']!='NULL'){
                    $equipo->ID_NOTEBOOK=$params_array['ID_NOTEBOOK'];
                }
                if($params_array['ID_TECLADO']!='NULL'){
                    $equipo->ID_TECLADO=$params_array['ID_TECLADO'];
                }
                if($params_array['ID_MONITOR']!='NULL'){
                    $equipo->ID_MONITOR=$params_array['ID_MONITOR'];
                }
                if($params_array['ID_MOUSE']!='NULL'){
                    $equipo->ID_MOUSE=$params_array['ID_MOUSE'];
                }
                if($params_array['ID_DESKTOP']!='NULL'){
                    $equipo->ID_DESKTOP=$params_array['ID_DESKTOP'];
                }
                if($params_array['ID_TELEFONO']!='NULL'){
                    $equipo->ID_TELEFONO=$params_array['ID_TELEFONO'];
                }
                $equipo->SW_NOTEBOOK=$params_array['SW_NOTEBOOK'];
                $equipo->SW_DESKTOP=$params_array['SW_DESKTOP'];
                $equipo->SW_TELEFONO=$params_array['SW_TELEFONO'];
                $equipo->OBSERVACIO=$params_array['OBSERVACIO'];
                if($params_array['DEVOLUCION']!='NULL'){
                    $equipo->DEVOLUCION=$params_array['DEVOLUCION'];
                }
                $usuario=$this->getIdentity($request);
                $equipo->ID_USUARIO=$$usuario;
                if($params_array['NOMBREUSUARIO']!='NULL'){
                    $equipo->NOMBREUSUARIO=$params_array['NOMBREUSUARIO'];
                }
                if($params_array['NTELEFONO']!='NULL'){
                    $equipo->NTELEFONO=$params_array['NTELEFONO'];
                }

                $equipo->NOM_ZON=$params_array['NOM_ZON'];
                $equipo->NOM_EMP=$params_array['NOM_EMP'];


                $equipo->save();

                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Equipo registrado correctamente',
                    'equipo'=>$params_array
                );
            }
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe ingresar datos para registrar un equipo'
            );
        }
        return response()->json($data,$data['code']);
    }

    private function getIdentity($request){
        //metodo para conseguir el usuario identificado
        $jwtAuth=new JwtAuth();
        $token=$request->header('Authorization',null);
        $user=$jwtAuth->checkToken($token,true);
        return $user;
    }

    public function update($id,Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDEMPRESA'=>'required',
                'IDZONA'=>'required',
                'OBSERVACIO'=>'required',
                'ID_USUARIO'=>'required',
                'NOM_ZON'=>'required',
                'NOM_EMP'=>'required',

            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'errors'=>$validate->errors()
                );
            }else{
                
                $equipo_update=Equipo::where('ID',$id)->update($params_array);
                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Equipo actualizado correctamente',
                    'equipo'=>$equipo_update
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

    public function destroy($id){
        $equipo=Equipo::where('ID',$id)->first();

        if(!empty($equipo)){
            $equipo->delete();
            $data=array(
                'status'=>'ok',
                'code'=>200,
                'message'=>'Equipo eliminado correctamente'
            );
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Equipo no existe'
            );
        }

        return response()->json($data,$data['code']);

    }
}
