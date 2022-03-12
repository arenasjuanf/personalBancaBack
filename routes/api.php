<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\AhorrosController;
use App\Http\Controllers\MontosController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['guest'])->group(function () {
    Route::post('registrar', [UsuariosController::class, 'registrar']);
    Route::get('login/{email}/{pass}', [UsuariosController::class, 'iniciarSesion']);
    
    //Ahorros
    Route::get('traerAhorros/{usuario}/{tipo}', [AhorrosController::class, 'traerAhorros']);
    Route::get('datosAhorro/{idAhorro}', [AhorrosController::class, 'datosAhorro']);
    Route::post('agregarAhorro', [AhorrosController::class, 'agregarAhorro']);
    Route::post('agregarDeuda', [AhorrosController::class, 'agregarDeuda']);
    Route::delete('borrarAhorro', [AhorrosController::class, 'borrarAhorro']);

    //Montos
    Route::get('datosMontos/{idAhorro}', [MontosController::class, 'datosMontos']);
    Route::post('agregarMonto', [MontosController::class, 'agregarMonto']);
    Route::put('actualizarMonto', [MontosController::class, 'actualizarMonto']);

    Route::get('conexion', [UsuariosController::class, 'conexion']);
    Route::get('versionActual', [UsuariosController::class, 'versionActual']);
    Route::get('enviarPin/{correo}', [UsuariosController::class, 'enviarPin']);
    Route::get('validarPin/{email}/{pin}', [UsuariosController::class, 'validarPin']); 
    Route::put('nuevaPassword', [UsuariosController::class, 'nuevaPassword']);  

});