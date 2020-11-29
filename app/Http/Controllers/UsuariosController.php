<?php

namespace App\Http\Controllers;

use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mail;


class UsuariosController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
			//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
			//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
			//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\Usuarios  $usuarios
	 * @return \Illuminate\Http\Response
	 */
	public function show(Usuarios $usuarios)
	{
			//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\Models\Usuarios  $usuarios
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Usuarios $usuarios)
	{
			//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Usuarios  $usuarios
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Usuarios $usuarios)
	{
			//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Usuarios  $usuarios
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Usuarios $usuarios)
	{
			//
	}

	public function registrar(Request $request){
		$resp["success"] = false;
			
		$validarEmail = Usuarios::where('email', $request->email)->get();

		if($validarEmail->isEmpty()){
      $usuario = new Usuarios;
      $usuario->email = $request->email;
      $usuario->nombres = $request->nombres;
      $usuario->apellidos = $request->apellidos;
      $usuario->pass = Hash::make($request->pass, ['rounds' => 15]);
			$usuario->pin = '0000';
			
			DB::beginTransaction();
      
      if($usuario->save()){	
				DB::commit();
        $resp["success"] = true;
        $resp["msj"] = "Se ha registrado correctamente";
      }else{
				DB::rollback();
        $resp["msj"] = "No ha registrado";
      }
    }else{
      $resp["msj"] = "El correo ya se encuentra registrado";
		}	
		
		return $resp;
	}

	public function conexion(){
		return 1;
	}

	public function iniciarSesion($email, $pass){
		$resp['success'] = false;

		$usuario = Usuarios::where(array('email' => $email))->first(); 

		if (is_object($usuario)) {
			if (Hash::check($pass, $usuario->pass)) {
				$resp["success"] = true;
				$resp['msj'] = $usuario;
			} else {
				$resp['msj'] = "Contraseña incorrecta";
			}
		} else{
			$resp['msj'] = "Correo no registrado";
		}

		return $resp;
	}

	public function enviarPin($correo){
		$resp["success"] = false;
		$pin = rand( 0 ,  99999 );
		$data = array("pin" => $pin);
		$subject = "Pin recuperacion password - PersonalBanca";
		$for = $correo;
		Mail::send('email', $data, function($msj) use($subject,$for){
				$msj->from("hysoporte018000@gmail.com","Personal Banca");
				$msj->subject($subject);
				$msj->to($for);
		});

		$usuario = Usuarios::where("email", $correo)->first();

		$usuario->pin = $pin;

		DB::beginTransaction();

		if ($usuario->save()) {
			DB::commit();
			$resp["success"] = true;
			$resp["msj"] = "Se ha enviado correctamente";
		} else {
			DB::rollback();
			$resp["msj"] = "Error al enviar correo";
		}

		return $resp;
	}

	public function validarPin($email, $pin){
		$resp["success"] = false;
		$validarPin = Usuarios::where([
			['email', $email],
			['pin', $pin]
		])->count();

		if ($validarPin == 1) {
			$resp["success"] = true;
			$resp["msj"] = "Autenticado";
		} else {
			$res["msj"] = "Error al autenticar";
		}
		
		return $resp;

	}

	public function nuevaPassword(Request $request) {

		$resp["success"] = false;

		$usuario = Usuarios::where("email", $request->email)->first();

		$usuario->pass = Hash::make($request->pass, ['rounds' => 15]);

		DB::beginTransaction();

		if ($usuario->save()) {
			DB::commit();
			$resp["success"] = true;
		} else {
			DB::rollback();
		}

		return $resp;
	}
	
	public function versionActual(){
		return '1.0.2';
	}

}
