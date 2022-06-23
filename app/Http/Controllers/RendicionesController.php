<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rendiciones;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
class RendicionesController extends Controller
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
                $rendiciones=Rendiciones::where('COD_EMP',$COD_EMP)
                            ->where('COD_TEM',$COD_TEM)
                            ->get();
                if(!empty($rendiciones)){
                    $data=array(
                        'status'=>'ok',
                        'code'=>200,
                        'rendiciones'=>$rendiciones
                    );
                }else{
                    $data=array(
                        'status'=>'error',
                        'code'=>400,
                        'message'=>'No hay rendiciones  en esa empresa y temporada'
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
    public function rendiciontrabajador(Request $request){
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);
        $params=json_decode($json);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'IDTRABAJADOR'=>'required',
                'TIPO'=>'required'
            ]);

            // TIPO = 0  // DEVUELVE SELECT DE RENDICIONES DEL TRABAJADOR
            // TIPO = 1 // DEVULVE EL NREPORT +1 DEL TRABAJADOR

            if($validate->fails()){
                $data=array(
                    'status'=>'ok',
                    'code'=>400,
                    'message'=>'Debe enviar Empresa , Temporada , IdTrabajador, Tipo'
                );
            }else{
                $COD_EMP=$params_array['COD_EMP'];
                $COD_TEM=$params_array['COD_TEM'];
                $IDTRABAJADOR=$params_array['IDTRABAJADOR'];
                $TIPO=$params_array['TIPO'];

                if($TIPO=='0'){
                    $rendiciones=Rendiciones::where('COD_EMP',$COD_EMP)
                    ->where('COD_TEM',$COD_TEM)
                    ->where('IDTRABAJADOR',$IDTRABAJADOR)
                    ->get();
                }else{

                $sql="SELECT ISNULL(MAX(CONVERT(INT,NRENDICION)),0) +1 AS NRENDICION
                     FROM APP_TransRENDICIONESCHOFERES
                     WHERE COD_EMP='{$COD_EMP}' AND COD_TEM='{$COD_TEM}'";
                $rendiciones=DB::connection('sqlsrv3')->select($sql);

            }

                if(!empty($rendiciones)){
                    $data=array(
                        'code'=>200,
                        'status'=>'ok',
                        'rendiones'=>$rendiciones
                    );
                }else{
                    $data=array(
                        'code'=>400,
                        'status'=>'error',
                        'message'=>'Sin rendiciones reportadas por el trabajador'
                    );
                }

            }
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe enviar parametros'
            );
        }
        return response()->json($data,$data['code']);
    }

    public function store(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);
        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'IDTRABAJADOR'=>'required',
                'IDMANGASTO'=>'required',
                'FECHA'=>'required',
                'NREPORT'=>'required',
                'NRENDICION'=>'required',
                'TOTAL'=>'required',
            //   'OBSERVACION'=>'required',
            ]);

            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Error en validacion de los datos ingresados',
                    'error'=>$validate->errors()
                );
            }else{
                $COD_EMP=$params_array['COD_EMP'];
                $COD_TEM=$params_array['COD_TEM'];
                $IDMANGASTO=$params_array['IDMANGASTO'];
                $IDTRABAJADOR=$params_array['IDTRABAJADOR'];
                $FECHA=$params_array['FECHA'];
                $NREPORT=$params_array['NREPORT'];
                $NRENDICION=$params_array['NRENDICION'];
                $TOTAL=$params_array['TOTAL'];
                $OBSERVACION=$params_array['OBSERVACION'];
                $CODIGOCLIENTE=$params_array['CODIGOCLIENTE'];
                $COD_MAQ=$params_array['COD_MAQ'];
                $SUBITEM=$params_array['SUBITEM'];
                $CANTIDAD=$params_array['CANTIDAD'];
                $VALOR=$params_array['VALOR'];
                $ODOMETRO=$params_array['ODOMETRO'];
                $HOROMETRO=$params_array['HOROMETRO'];
                $USUARIO=$params_array['USUARIO'];
                $ENTREGADINERO='0';
                if($IDMANGASTO=='5'){
                    $ENTREGADINERO='1';
                }
                $NUEVAFECHA=str_replace('/','',$FECHA);
                $IDFOTO=$COD_EMP.$COD_TEM.$IDTRABAJADOR.$IDMANGASTO.$NUEVAFECHA.$ENTREGADINERO.$NREPORT;

                $rendicion=new Rendiciones();
                $rendicion->COD_EMP=$COD_EMP;
                $rendicion->COD_TEM=$COD_TEM;
                $rendicion->IDMANGASTO=$IDMANGASTO;
                $rendicion->IDTRABAJADOR=$IDTRABAJADOR;
                $rendicion->IDFOTO=$IDFOTO;
                $rendicion->FECHA=$FECHA;
                $rendicion->ENTREGADINERO=$ENTREGADINERO;
                $rendicion->NREPORT=$NREPORT;
                $rendicion->NRENDICION=$NRENDICION;
                $rendicion->TOTAL=$TOTAL;
                if(!empty($OBSERVACION)){
                    $rendicion->OBSERVACION=$OBSERVACION;
                }

                $rendicion->USUARIO=$USUARIO;
                $rendicion->FECHASISTEMA=date("d-m-Y H:i:s",time());
                if(!empty($COD_MAQ)){
                   $rendicion->CODIGOCLIENTE=$CODIGOCLIENTE;
                   $rendicion->COD_MAQ=$COD_MAQ;
                   $rendicion->SUBITEM=$SUBITEM;
                   $rendicion->CANTIDAD=$CANTIDAD;
                   $rendicion->VALOR=$VALOR;
                   $rendicion->ODOMETRO=$ODOMETRO;
                   $rendicion->HOROMETRO=$HOROMETRO;
                }
                $rendicion->save();

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
                'message'=>'Debe enviar parametros'
            );
        }

        return response()->json($data,$data['code']);
    }
    public function upload(Request $request){
        //recoger la imgen de la peticion


        $image=$request->file('file0');
        $token=$request->header('name');




        //validar imagen
        $validate=\Validator::make($request->all(),[
            'file0'=>'required|image|mimes:jpg,gif,jpeg,png'
        ]);

        //guardar en disco images
        if(!$image||$validate->fails()){
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Error al subir la imagen seleccionada',
                'error'=>$validate->errors()
            );
        }else{
           // $image_name=time().$image->getClientOriginalName();
            $image_name=$token.".jpg";
            \Storage::disk('images')->put($image_name,\File::get($image));
           $data=array(
               'code'=>200,
               'status'=>'succes',
               'image'=>$image_name
           );

        }
        //retornar datos
        return response()->json($data,$data['code']);
    }

    public function getImage($filename){
        //comprobar si existe el fichero
        $filename=$filename.".jpg";

        $isset=\Storage::disk('images')->exists($filename);
        if($isset){
            //conseguir la imagen
            $file=\Storage::disk('images')->get($filename);
            //devolver la imagen
            return new Response($file,200);
        }else{
            $data=[
                'code'=>404,
                'status'=>'error',
                'message'=>'La imagen no existe'
            ];
              //mostrar resultado
            return response()->json($data,$data['code']);
        }

    }


    public function getrendiciones(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'IDUSUARIO'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Error en datos enviados',
                    'errores'=>$validate->errors()
                );
            }else{
                $COD_EMP=$params_array['COD_EMP'];
                $COD_TEM=$params_array['COD_TEM'];
                $IDUSUARIO=$params_array['IDUSUARIO'];
                $sql="SELECT[Empresa]= E.NOM_EMP,[Temporada]=R.COD_TEM,[Id Rendidior]=R.IDTRABAJADOR, [Rendidor]=T.NOMBRE+' '+T.APELLIDOPATERNO+' '+T.APELLIDOMATERNO,
                    [Id Gasto]=R.IDMANGASTO, [Item]=G.DESCRIPCION, [Año]=YEAR(R.FECHA), [Mes]=DATENAME(mm,R.FECHA), [Fecha]=CONVERT(VARCHAR,R.FECHA,103),
                    [Tipo]=CASE WHEN R.ENTREGADINERO=1 THEN 'Entrega Dinero' ELSE 'Rendicion Gasto' END,
                    [NRendicion]=R.NRENDICION, [NReport]=R.NREPORT,
                    [Centro Costo]=LTRIM(STR(cc.COD_CENTROCOSTO))+' '+ CC.DESCRIPCION,
                    [Sub Centro]=LTRIM(STR(sb.COD_SUBCENTRO))+' '+SB.DESCRIPCION,
                    [Total]=R.TOTAL*CASE WHEN R.ENTREGADINERO=1 THEN 1 ELSE 1 END,
                    [Observacion]=R.OBSERVACION
                    ,ISNULL(ISNULL(B.COD_FAM+B.COD_SUBFAM+B.COD_CUENTA,'') + ' ' + B.NOM_CUENTA,'') AS [CUENTA CONTABLE]
                    ,TOTAL_US =R.TOTAL/D.DOLAR	, R.CODIGOCLIENTE, R.CANTIDAD, R.HOROMETRO, R.ODOMETRO, G.SW_COMBUSTIBLE, R.APROBACION, R.IMPORTADO,JD.UsuarioSis,R.ENTREGADINERO,R.COD_EMP,R.IDFOTO
                    FROM APP_TRANSRENDICIONESCHOFERES R
                    INNER JOIN TransManGASTOS_OT G ON G.COD_EMP=R.COD_EMP AND G.COD_TEM=R.COD_TEM AND G.IDMANGASTO=R.IDMANGASTO
                    LEFT JOIN CUENTA_CONT B  ON G.COD_EMP = B.COD_EMP AND G.COD_FAM = B.COD_FAM AND G.COD_SUBFAM = B.COD_SUBFAM AND G.COD_CUENTA = B.COD_CUENTA
                    INNER JOIN EMPRESAS E ON E.COD_EMP=R.COD_EMP
                    INNER JOIN Bsis_Rem_Afr.dbo.Trabajador T ON T.IDEMPRESA=E.ID_EMPRESA_REM AND T.IDTRABAJADOR=R.IDTRABAJADOR
                    INNER JOIN Bsis_Rem_Afr.dbo.Contratos C WITH(NOLOCK) ON T.IdTrabajador = C.IdTrabajador AND T.IdEmpresa = C.IdEmpresa  and r.fecha BETWEEN c.fechainicio and isnull(c.fechatermino,isnull(c.fechaterminoc,getDATE()))
                    left JOIN BSIS_REM_AFR.DBO.TRABAJADOR JD WITH(NOLOCK) ON c.IDEMPRESA_JEFEDIRECTO=JD.IDEMPRESA AND c.IDJEFEDIRECTO=JD.IDTRABAJADOR
                    LEFT JOIN Bsis_Rem_Afr.dbo.Zona ON c.IdEmpresa = Zona.IdEmpresa AND c.IdZona = Zona.IdZona
                    LEFT JOIN Bsis_Rem_Afr.dbo.Cuartel ON c.IdEmpresa = Cuartel.IdEmpresa AND c.IdZona = Cuartel.IdZona AND c.IdCuartel = Cuartel.IdCuartel
                    LEFT JOIN CENTROCOSTO_CONT CC ON CC.COD_EMP=R.COD_EMP AND CC.COD_CENTROCOSTO=zona.COD_CENTROCOSTO
                    LEFT JOIN SUB_CENTROCOSTO SB ON SB.COD_EMP=CC.COD_EMP AND SB.COD_CENTROCOSTO=CC.COD_CENTROCOSTO AND SB.COD_SUBCENTRO=cuartel.COD_SUBCENTRO
                    LEFT JOIN DOLAR D ON D.COD_PAIS=E.COD_PAIS AND D.FECHA=R.FECHA
                    WHERE R.COD_EMP='$COD_EMP' AND R.COD_TEM='$COD_TEM'  and JD.UsuarioSis='$IDUSUARIO' and R.APROBACION='0' ORDER BY R.NRENDICION  ";

                $rendiciones=DB::connection('sqlsrv3')->select($sql);

                $data=array(
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>'Rendiciones encontradas',
                    'rendiciones'=>$rendiciones
                );

            }
        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Debe enviar empresa , temporada, usuario'
            );
        }


        return response()->json($data,$data['code']);



    }

    public function getrendicionesresumen(Request $request){
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);
        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'IDUSUARIO'=>'required'
            ]);


            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'error'=>$validate->errors()
                );
            }else{
                $COD_EMP=$params_array['COD_EMP'];
                $COD_TEM=$params_array['COD_TEM'];
                $IDUSUARIO=$params_array['IDUSUARIO'];

                    $sql="SELECT [Empresa]= E.NOM_EMP,[Temporada]=R.COD_TEM,[IdRendidior]=R.IDTRABAJADOR, [Rendidor]=T.NOMBRE+' '+T.APELLIDOPATERNO+' '+T.APELLIDOMATERNO,[NRendicion]=convert(int,R.NRENDICION),[Total]=SUM(R.TOTAL)
                    FROM APP_TRANSRENDICIONESCHOFERES R
                    INNER JOIN EMPRESAS E ON E.COD_EMP=R.COD_EMP
                    INNER JOIN Bsis_Rem_Afr.dbo.Trabajador T ON T.IDEMPRESA=E.ID_EMPRESA_REM AND T.IDTRABAJADOR=R.IDTRABAJADOR
                    INNER JOIN Bsis_Rem_Afr.dbo.Contratos C WITH(NOLOCK) ON T.IdTrabajador = C.IdTrabajador AND T.IdEmpresa = C.IdEmpresa  and r.fecha BETWEEN c.fechainicio and isnull(c.fechatermino,isnull(c.fechaterminoc,getDATE()))
                    left JOIN BSIS_REM_AFR.DBO.TRABAJADOR JD WITH(NOLOCK) ON c.IDEMPRESA_JEFEDIRECTO=JD.IDEMPRESA AND c.IDJEFEDIRECTO=JD.IDTRABAJADOR
                    WHERE R.COD_EMP='$COD_EMP' AND R.COD_TEM='$COD_TEM'  and JD.UsuarioSis='$IDUSUARIO' and R.APROBACION='0'
                    GROUP BY R.NRENDICION,E.NOM_EMP,R.COD_TEM,R.IDTRABAJADOR,T.Nombre,T.ApellidoPaterno,T.ApellidoMaterno
                    ORDER BY NRendicion DESC";

                $rendiciones=DB::connection('sqlsrv3')->select($sql);

                $data=array(
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>'Rendiciones encontradas',
                    'rendiciones'=>$rendiciones
                );
            }

        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe enviar datos a consultar'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function getmisrendicionesresumen(Request $request){
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);
        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'IDTRABAJADOR'=>'required'
            ]);


            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error validando datos',
                    'error'=>$validate->errors()
                );
            }else{
                $COD_EMP=$params_array['COD_EMP'];
                $COD_TEM=$params_array['COD_TEM'];
                $IDTRABAJADOR=$params_array['IDTRABAJADOR'];

                $sql="SELECT [Empresa]= E.NOM_EMP,[Temporada]=R.COD_TEM,[IdRendidior]=R.IDTRABAJADOR, [Rendidor]=T.NOMBRE+' '+T.APELLIDOPATERNO+' '+T.APELLIDOMATERNO,[NRendicion]=convert(int,R.NRENDICION),[Total]=SUM(R.TOTAL)
                FROM APP_TRANSRENDICIONESCHOFERES R
                INNER JOIN EMPRESAS E ON E.COD_EMP=R.COD_EMP
                INNER JOIN Bsis_Rem_Afr.dbo.Trabajador T ON T.IDEMPRESA=E.ID_EMPRESA_REM AND T.IDTRABAJADOR=R.IDTRABAJADOR
                INNER JOIN Bsis_Rem_Afr.dbo.Contratos C WITH(NOLOCK) ON T.IdTrabajador = C.IdTrabajador AND T.IdEmpresa = C.IdEmpresa  and r.fecha BETWEEN c.fechainicio and isnull(c.fechatermino,isnull(c.fechaterminoc,getDATE()))
                left JOIN BSIS_REM_AFR.DBO.TRABAJADOR JD WITH(NOLOCK) ON c.IDEMPRESA_JEFEDIRECTO=JD.IDEMPRESA AND c.IDJEFEDIRECTO=JD.IDTRABAJADOR
                WHERE R.COD_EMP='$COD_EMP' AND R.COD_TEM='$COD_TEM'  and  R.IDTRABAJADOR='$IDTRABAJADOR' and R.APROBACION='0'
                GROUP BY R.NRENDICION,E.NOM_EMP,R.COD_TEM,R.IDTRABAJADOR,T.Nombre,T.ApellidoPaterno,T.ApellidoMaterno
                ORDER BY NRendicion DESC";

                $rendiciones=DB::connection('sqlsrv3')->select($sql);

                $data=array(
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>'Rendiciones encontradas',
                    'rendiciones'=>$rendiciones
                );
            }

        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Debe enviar datos a consultar'
            );
        }

        return response()->json($data,$data['code']);
    }

    public function aprobarrendicion(Request $request){
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDFOTO'=>'required'

            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error en validacion de datos enviados',
                    'error'=>$validate->errors()
                );
            }else{
                $idfoto=$params_array['IDFOTO'];
                $hoy = date("d-m-Y H:i:s",time());
                $sql="UPDATE APP_TRANSRENDICIONESCHOFERES
                 SET APROBACION='1' ,FECHA_APROBACION='{$hoy}'
                 WHERE IDFOTO='$idfoto' ";
                $rendiciones=DB::connection('sqlsrv3')->update($sql);

                 $data=array(
                     'code'=>200,
                     'status'=>'ok',
                     'message'=>'Rendicion actualizada'
                 );

            }
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'messasge'=>'Debe enviar codigo para eliminar rendicion'
            );
        }
        return response()->json($data,$data['code']);
    }


    public function detallerendiciones(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'IDUSUARIO'=>'required',
                'IDRENDIDOR'=>'required',
                'NRENDICION'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Error en datos enviados',
                    'errores'=>$validate->errors()
                );
            }else{
                $COD_EMP=$params_array['COD_EMP'];
                $COD_TEM=$params_array['COD_TEM'];
                $IDUSUARIO=$params_array['IDUSUARIO'];
                $IDTRABAJADOR=$params_array['IDRENDIDOR'];
                $NRENDICION=$params_array['NRENDICION'];
                $sql="SELECT[Empresa]= E.NOM_EMP,[Temporada]=R.COD_TEM,[Id Rendidior]=R.IDTRABAJADOR, [Rendidor]=T.NOMBRE+' '+T.APELLIDOPATERNO+' '+T.APELLIDOMATERNO,
                    [Id Gasto]=R.IDMANGASTO, [Item]=G.DESCRIPCION, [Año]=YEAR(R.FECHA), [Mes]=DATENAME(mm,R.FECHA), [Fecha]=CONVERT(VARCHAR,R.FECHA,103),
                    [Tipo]=CASE WHEN R.ENTREGADINERO=1 THEN 'Entrega Dinero' ELSE 'Rendicion Gasto' END,
                    [NRendicion]=R.NRENDICION, [NReport]=R.NREPORT,
                    [Centro Costo]=LTRIM(STR(cc.COD_CENTROCOSTO))+' '+ CC.DESCRIPCION,
                    [Sub Centro]=LTRIM(STR(sb.COD_SUBCENTRO))+' '+SB.DESCRIPCION,
                    [Total]=R.TOTAL*CASE WHEN R.ENTREGADINERO=1 THEN 1 ELSE 1 END,
                    [Observacion]=R.OBSERVACION
                    ,ISNULL(ISNULL(B.COD_FAM+B.COD_SUBFAM+B.COD_CUENTA,'') + ' ' + B.NOM_CUENTA,'') AS [CUENTA CONTABLE]
                    ,TOTAL_US =R.TOTAL/D.DOLAR	, R.CODIGOCLIENTE, R.CANTIDAD, R.HOROMETRO, R.ODOMETRO, G.SW_COMBUSTIBLE, R.APROBACION, R.IMPORTADO,JD.UsuarioSis,R.ENTREGADINERO,R.COD_EMP,R.IDFOTO
                    FROM APP_TRANSRENDICIONESCHOFERES R
                    INNER JOIN TransManGASTOS_OT G ON G.COD_EMP=R.COD_EMP AND G.COD_TEM=R.COD_TEM AND G.IDMANGASTO=R.IDMANGASTO
                    LEFT JOIN CUENTA_CONT B  ON G.COD_EMP = B.COD_EMP AND G.COD_FAM = B.COD_FAM AND G.COD_SUBFAM = B.COD_SUBFAM AND G.COD_CUENTA = B.COD_CUENTA
                    INNER JOIN EMPRESAS E ON E.COD_EMP=R.COD_EMP
                    INNER JOIN Bsis_Rem_Afr.dbo.Trabajador T ON T.IDEMPRESA=E.ID_EMPRESA_REM AND T.IDTRABAJADOR=R.IDTRABAJADOR
                    INNER JOIN Bsis_Rem_Afr.dbo.Contratos C WITH(NOLOCK) ON T.IdTrabajador = C.IdTrabajador AND T.IdEmpresa = C.IdEmpresa  and r.fecha BETWEEN c.fechainicio and isnull(c.fechatermino,isnull(c.fechaterminoc,getDATE()))
                    left JOIN BSIS_REM_AFR.DBO.TRABAJADOR JD WITH(NOLOCK) ON c.IDEMPRESA_JEFEDIRECTO=JD.IDEMPRESA AND c.IDJEFEDIRECTO=JD.IDTRABAJADOR
                    LEFT JOIN Bsis_Rem_Afr.dbo.Zona ON c.IdEmpresa = Zona.IdEmpresa AND c.IdZona = Zona.IdZona
                    LEFT JOIN Bsis_Rem_Afr.dbo.Cuartel ON c.IdEmpresa = Cuartel.IdEmpresa AND c.IdZona = Cuartel.IdZona AND c.IdCuartel = Cuartel.IdCuartel
                    LEFT JOIN CENTROCOSTO_CONT CC ON CC.COD_EMP=R.COD_EMP AND CC.COD_CENTROCOSTO=zona.COD_CENTROCOSTO
                    LEFT JOIN SUB_CENTROCOSTO SB ON SB.COD_EMP=CC.COD_EMP AND SB.COD_CENTROCOSTO=CC.COD_CENTROCOSTO AND SB.COD_SUBCENTRO=cuartel.COD_SUBCENTRO
                    LEFT JOIN DOLAR D ON D.COD_PAIS=E.COD_PAIS AND D.FECHA=R.FECHA
                    WHERE R.COD_EMP='$COD_EMP' AND R.COD_TEM='$COD_TEM'  and JD.UsuarioSis='$IDUSUARIO' and R.APROBACION='0'AND R.IDTRABAJADOR='$IDTRABAJADOR' AND R.NRENDICION='$NRENDICION'  ORDER BY R.NRENDICION  ";

                $rendiciones=DB::connection('sqlsrv3')->select($sql);

                $data=array(
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>'Rendiciones encontradas',
                    'rendiciones'=>$rendiciones
                );

            }
        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Debe enviar empresa , temporada, usuario'
            );
        }


        return response()->json($data,$data['code']);

    }

    public function aprobarrendicionresumen(Request $request){
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'IDRENDIDOR'=>'required',
                'NRENDICION'=>'required'

            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Error en validacion de datos enviados',
                    'error'=>$validate->errors()
                );
            }else{
                $COD_EMP=$params_array['COD_EMP'];
                $COD_TEM=$params_array['COD_TEM'];
                $IDRENDIDOR=$params_array['IDRENDIDOR'];
                $NRENDICION=$params_array['NRENDICION'];

                $hoy = date("d-m-Y H:i:s",time());
                $sql="UPDATE APP_TRANSRENDICIONESCHOFERES
                 SET APROBACION='1' ,FECHA_APROBACION='{$hoy}'
                 WHERE COD_EMP='$COD_EMP' AND COD_TEM='$COD_TEM' AND IDTRABAJADOR='$IDRENDIDOR' AND NRENDICION='$NRENDICION' ";
                $rendiciones=DB::connection('sqlsrv3')->update($sql);

                 $data=array(
                     'code'=>200,
                     'status'=>'ok',
                     'message'=>'Rendicion actualizada'
                 );

            }
        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'messasge'=>'Debe enviar codigo para eliminar rendicion'
            );
        }
        return response()->json($data,$data['code']);
    }

    public function eliminarrendicion(Request $request){
        $json=$request->input('json',null);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'IDFOTO'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'status'=>'error',
                    'code'=>400,
                    'message'=>'Validation fails',
                    'errors'=>$validate->errors()
                );
            }else{
                $filename=$params_array['IDFOTO'].".jpg";

                $isset=\Storage::disk('images')->exists($filename);
                if($isset){
                    //conseguir la imagen
                    $file=\Storage::disk('images')->delete($filename);
                    //devolver la imagen

                }
                $rendicion=Rendiciones::where('IDFOTO',$params_array['IDFOTO'])->delete();
                $data=array(
                    'status'=>'ok',
                    'code'=>200,
                    'message'=>'Rendition delete correctly'
                );


            }


        }else{
            $data=array(
                'status'=>'error',
                'code'=>400,
                'message'=>'Should send data'
            );
        }

        return response()->json($data,$data['code']);


    }
    public function misrendiciones(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty($params_array)){
            $validate=\Validator::make($params_array,[
                'COD_EMP'=>'required',
                'COD_TEM'=>'required',
                'IDRENDIDOR'=>'required',
                'NRENDICION'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'message'=>'Error en datos enviados de mi rendicion',
                    'errores'=>$validate->errors()
                );
            }else{
                $COD_EMP=$params_array['COD_EMP'];
                $COD_TEM=$params_array['COD_TEM'];

                $IDTRABAJADOR=$params_array['IDRENDIDOR'];
                $NRENDICION=$params_array['NRENDICION'];
                $sql="SELECT[Empresa]= E.NOM_EMP,[Temporada]=R.COD_TEM,[Id Rendidior]=R.IDTRABAJADOR, [Rendidor]=T.NOMBRE+' '+T.APELLIDOPATERNO+' '+T.APELLIDOMATERNO,
                    [Id Gasto]=R.IDMANGASTO, [Item]=G.DESCRIPCION, [Año]=YEAR(R.FECHA), [Mes]=DATENAME(mm,R.FECHA), [Fecha]=CONVERT(VARCHAR,R.FECHA,103),
                    [Tipo]=CASE WHEN R.ENTREGADINERO=1 THEN 'Entrega Dinero' ELSE 'Rendicion Gasto' END,
                    [NRendicion]=R.NRENDICION, [NReport]=R.NREPORT,
                    [Centro Costo]=LTRIM(STR(cc.COD_CENTROCOSTO))+' '+ CC.DESCRIPCION,
                    [Sub Centro]=LTRIM(STR(sb.COD_SUBCENTRO))+' '+SB.DESCRIPCION,
                    [Total]=R.TOTAL*CASE WHEN R.ENTREGADINERO=1 THEN 1 ELSE 1 END,
                    [Observacion]=R.OBSERVACION
                    ,ISNULL(ISNULL(B.COD_FAM+B.COD_SUBFAM+B.COD_CUENTA,'') + ' ' + B.NOM_CUENTA,'') AS [CUENTA CONTABLE]
                    ,TOTAL_US =R.TOTAL/D.DOLAR	, R.CODIGOCLIENTE, R.CANTIDAD, R.HOROMETRO, R.ODOMETRO, G.SW_COMBUSTIBLE, R.APROBACION, R.IMPORTADO,JD.UsuarioSis,R.ENTREGADINERO,R.COD_EMP,R.IDFOTO
                    FROM APP_TRANSRENDICIONESCHOFERES R
                    INNER JOIN TransManGASTOS_OT G ON G.COD_EMP=R.COD_EMP AND G.COD_TEM=R.COD_TEM AND G.IDMANGASTO=R.IDMANGASTO
                    LEFT JOIN CUENTA_CONT B  ON G.COD_EMP = B.COD_EMP AND G.COD_FAM = B.COD_FAM AND G.COD_SUBFAM = B.COD_SUBFAM AND G.COD_CUENTA = B.COD_CUENTA
                    INNER JOIN EMPRESAS E ON E.COD_EMP=R.COD_EMP
                    INNER JOIN Bsis_Rem_Afr.dbo.Trabajador T ON T.IDEMPRESA=E.ID_EMPRESA_REM AND T.IDTRABAJADOR=R.IDTRABAJADOR
                    INNER JOIN Bsis_Rem_Afr.dbo.Contratos C WITH(NOLOCK) ON T.IdTrabajador = C.IdTrabajador AND T.IdEmpresa = C.IdEmpresa  and r.fecha BETWEEN c.fechainicio and isnull(c.fechatermino,isnull(c.fechaterminoc,getDATE()))
                    left JOIN BSIS_REM_AFR.DBO.TRABAJADOR JD WITH(NOLOCK) ON c.IDEMPRESA_JEFEDIRECTO=JD.IDEMPRESA AND c.IDJEFEDIRECTO=JD.IDTRABAJADOR
                    LEFT JOIN Bsis_Rem_Afr.dbo.Zona ON c.IdEmpresa = Zona.IdEmpresa AND c.IdZona = Zona.IdZona
                    LEFT JOIN Bsis_Rem_Afr.dbo.Cuartel ON c.IdEmpresa = Cuartel.IdEmpresa AND c.IdZona = Cuartel.IdZona AND c.IdCuartel = Cuartel.IdCuartel
                    LEFT JOIN CENTROCOSTO_CONT CC ON CC.COD_EMP=R.COD_EMP AND CC.COD_CENTROCOSTO=zona.COD_CENTROCOSTO
                    LEFT JOIN SUB_CENTROCOSTO SB ON SB.COD_EMP=CC.COD_EMP AND SB.COD_CENTROCOSTO=CC.COD_CENTROCOSTO AND SB.COD_SUBCENTRO=cuartel.COD_SUBCENTRO
                    LEFT JOIN DOLAR D ON D.COD_PAIS=E.COD_PAIS AND D.FECHA=R.FECHA
                    WHERE R.COD_EMP='$COD_EMP' AND R.COD_TEM='$COD_TEM' and R.APROBACION='0'AND R.IDTRABAJADOR='$IDTRABAJADOR' AND R.NRENDICION='$NRENDICION'  ORDER BY R.NRENDICION  ";

                $rendiciones=DB::connection('sqlsrv3')->select($sql);

                $data=array(
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>'Rendiciones encontradas',
                    'rendiciones'=>$rendiciones
                );

            }
        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Debe enviar empresa , temporada, usuario'
            );
        }


        return response()->json($data,$data['code']);

    }
    public function enviafotorendicion(Request $request){
        $json=$request->input('json',null);
        $params=json_decode($json);
        $params_array=json_decode($json,true);

        if(!empty( $params_array)){
            $validate=\Validator::make($params_array,[
                'imagen'=>'required',
                'nombre'=>'required'
            ]);

            if($validate->fails()){
                $data=array(
                    'code'=>400,
                    'status'=>'error',
                    'mesage'=>'Error en validacion de datos'
                );
            }else{
                $imagen=$params_array['imagen'];
                $nombreimagen=$params_array['nombre'];
                $ruta_imagen="api-app/storage/app/images/$nombreimagen.jpg";
                file_put_contents($ruta_imagen,base64_decode($imagen));
                $bytesArchivo=file_get_contents($ruta_imagen);

                if($bytesArchivo){
                    $data=array(
                        'code'=>200,
                        'status'=>'ok',
                        'message'=>'Foto enviada correctamente'
                    );
                }else{
                    $data=array(
                        'code'=>400,
                        'status'=>'error',
                        'message'=>'No se registro imagen'
                    );
                }
            }
        }else{
            $data=array(
                'code'=>400,
                'status'=>'error',
                'message'=>'Debe enviar dato de imagen, y nombre'
            );
        }

        return response()->json($data,$data['code']);



    }



}
