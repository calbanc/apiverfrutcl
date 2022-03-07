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

   Route::get('/locura', function () {
    //   return view('welcome');
    return 'hola mundo mundial   ';
   });


//Route::post('/api/register',[UserController::class,'register']);
Route::post('/api/rendimiento',[RendimientoController::class,'store']);
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

