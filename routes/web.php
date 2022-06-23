<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ProtocolosUsuarioController;
use App\Http\Controllers\DesktopController;
use App\Http\Controllers\MonitorController;
use App\Http\Controllers\MouseController;
use App\Http\Controllers\NotebookController;
use App\Http\Controllers\TecladoController;
use App\Http\Controllers\TefonoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\AplicacionController;
use App\Http\Controllers\PerfilFrutSysController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ZonasController;
use App\Http\Controllers\EmpresaRemuneracionesController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\TransChoferesController;
use App\Http\Controllers\SubItemController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\RendicionesController;
use App\Http\Controllers\RendimientoController;
use App\Http\Controllers\ZonasRemuController;
use App\Http\Controllers\CuartelRemuController;
use App\Http\Controllers\TipoLineaPkController;
use App\Http\Controllers\TitPlanillaController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DetallePlanillaController;
use App\Http\Controllers\TitDatosController;
use App\Http\Controllers\RecepcionCamionesController;
use App\Http\Controllers\PackingsParametrosController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\MarcacionesController;
use App\Http\Controllers\EstacionMarcacionController;
use App\Http\Controllers\DetalleDatosController;
use App\Http\Controllers\RondinController;
use App\Http\Controllers\VelocidadesController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    //   return view('welcome');
    return 'hola mundo mundial   ';
});



//Route::post('/api/register',[UserController::class,'register']);
Route::post('/api/rendimiento',[RendimientoController::class,'store']);
Route::get('/api/planillas',[TitPlanillaController::class,'index']);
Route::get('/api/item',[ItemController::class,'index']);
Route::post('/api/planillasnew',[TitPlanillaController::class,'create']);
Route::post('/api/planillasdelete',[TitPlanillaController::class,'destroy']);
Route::post('/api/detalleplanillacreate',[DetallePlanillaController::class,'store'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/detalleplanilladelete',[DetallePlanillaController::class,'delete']);
Route::post('/api/titdatoscreate',[TitDatosController::class,'create']);
Route::post('/api/planillasemptemp',[TitPlanillaController::class,'listemptemp']);
Route::post('/api/planillas/detalle',[DetallePlanillaController::class,'detail']);
Route::post('/api/planillas/destroy',[TitPlanillaController::class,'destroy']);
Route::post('/api/planillas/detalledatos',[DetalleDatosController::class,'detail'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/planillas/listbyidplanilla',[DetalleDatosController::class,'listbyidplanilla'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);

Route::post('/api/rendimientotop',[RendimientoController::class,'top']);
Route::post('/api/login',[UserController::class,'login']);
Route::get('/api/perfil',[PerfilController::class,'index']);
Route::post('/api/temporada',[EmpresaController::class,'temporada'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/temporadaremuneraciones',[EmpresaRemuneracionesController::class,'temporada'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/perfil/registrar',[PerfilController::class,'store'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/perfil/usuario',[PerfilController::class,'buscaperfilusuario'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/perfil/sistema',[PerfilFrutSysController::class,'permisosapp'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::get('/api/perfil/aplicaciones',[PerfilFrutSysController::class,'aplicacionesusuario'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/gastos',[TransChoferesController::class,'index'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/subitem',[SubItemController::class,'combustible'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/trabajadormaquina',[TrabajadorController::class,'maquinatrabajador'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/proveedores',[ProveedoresController::class,'index'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/rendiciones',[RendicionesController::class,'index'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/rendiciones/trabajador',[RendicionesController::class,'rendiciontrabajador'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/rendiciones/create',[RendicionesController::class,'store'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/zonasremu',[ZonasRemuController::class,'index'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::get('/api/app', [AppController::class,'index']);

//ºRoute::resource('/api/perfil',PerfilController::class);
Route::resource('/api/desktop',DesktopController::class)->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::resource('/api/monitor',MonitorController::class)->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::resource('/api/mouse',MouseController::class)->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::resource('/api/notebook',NotebookController::class)->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::resource('/api/teclado',TecladoController::class)->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::resource('/api/telefono',TefonoController::class)->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::resource('/api/equipo',EquipoController::class)->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::get('/api/empresa',[EmpresaController::class,'index'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/zonas',[ZonasController::class,'index'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::get('/api/empresarem',[EmpresaRemuneracionesController::class,'index'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/trabajador/',[TrabajadorController::class,'buscatrabajador'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);

Route::post('/api/cuartel',[CuartelRemuController::class,'index'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/lineas',[TipoLineaPkController::class,'index'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('api/imagen',[RendicionesController::class,'upload']);
Route::get('api/getimagen/{filename}',[RendicionesController::class,'getImage']);
Route::post('api/rendiciones/listado',[RendicionesController::class,'getrendiciones'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('api/rendiciones/resumen',[RendicionesController::class,'getrendicionesresumen'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('api/rendicion/aprobar',[RendicionesController::class,'aprobarrendicion'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('api/rendicion/aprobarresumen',[RendicionesController::class,'aprobarrendicionresumen'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('api/rendicion/eliminar',[RendicionesController::class,'eliminarrendicion'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('api/rendicion/detallerendicion',[RendicionesController::class,'detallerendiciones'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('api/rendicion/misrendicionesresumen',[RendicionesController::class,'getmisrendicionesresumen'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('api/rendicion/misrendiciones',[RendicionesController::class,'misrendiciones'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('api/rendicion/enviafoto',[RendicionesController::class,'enviafotorendicion'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);


//Recepcion camiones
Route::get('api/recepcioncamiones',[RecepcionCamionesController::class,'index']);
Route::post('api/recepcioncamiones/listbyemp',[RecepcionCamionesController::class, 'listbyemp'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);;
Route::post('api/recepcioncamiones/listbyempzon',[RecepcionCamionesController::class, 'listbyempzon'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);;
Route::post('api/recepcioncamiones/create',[RecepcionCamionesController::class,'create']);
Route::post('api/recepcioncamiones/searchbyrut',[RecepcionCamionesController::class,'searchbyrut']);

Route::post('api/packingparametro/getbyuseremptem',[PackingsParametrosController::class,'getbyuseremptem'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);




//Direcctorio
Route::get('/api/directorio/produccion/allempresas',[ProduccionController::class,'allempresas'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/directorio/produccion/temporadabyempresa',[ProduccionController::class,'temporadabyempresa'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/directorio/produccion/obtenerGrupoProductoresByTemporadasAndEmpresa',[ProduccionController::class,'obtenerGrupoProductoresByTemporadasAndEmpresa'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/directorio/produccion/obtenerProductoresByTemporadasAndEmpresa',[ProduccionController::class,'obtenerProductoresByTemporadasAndEmpresa'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/directorio/produccion/obtenerZonasByProductorTemporadasAndEmpresa',[ProduccionController::class,'obtenerZonasByProductorTemporadasAndEmpresa'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/directorio/produccion/obtenerZonasByProductorTemporadasAndEmpresa',[ProduccionController::class,'obtenerZonasByProductorTemporadasAndEmpresa'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/directorio/produccion/obtenerReportePorEspecie',[ProduccionController::class,'obtenerReportePorEspecie'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/directorio/produccion/obtenerReportePorVariedad',[ProduccionController::class,'obtenerReportePorVariedad'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/directorio/produccion/obtenerReporteTotales',[ProduccionController::class,'obtenerReporteTotales'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);



//Marcaciones -- Asistencia
Route::post('/api/marcaciones/searchbyrutdate',[MarcacionesController::class,'searchbyrutdate'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/marcaciones/searchbyestaciondate',[MarcacionesController::class,'searchbyestaciondate'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::get('/api/marcaciones/listestacionmarcacion',[EstacionMarcacionController::class,'index'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);

//Rondines -

Route::post('/api/rondines/allbyday',[RondinController::class,'allbyday'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/rondines/rondinbyday',[RondinController::class,'rondinbyday'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);


//Velocidades
Route::post('/api/velocidades/recordbydatestation',[VelocidadesController::class,'recordbydatestation'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
Route::post('/api/velocidades/recordbydatetimestation',[VelocidadesController::class,'recordbydatetimestation'])->middleware(\App\Http\Middleware\ApiAuthMiddleware::class);
