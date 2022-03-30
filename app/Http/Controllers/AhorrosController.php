<?php

namespace App\Http\Controllers;

use App\Models\Ahorros;
use App\Models\Montos;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AhorrosController extends Controller
{

    public function __construct() {
        $this->middleware('cors');
    }
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
     * @param  \App\Models\Ahorros  $ahorros
     * @return \Illuminate\Http\Response
     */
    public function show(Ahorros $ahorros)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ahorros  $ahorros
     * @return \Illuminate\Http\Response
     */
    public function edit(Ahorros $ahorros)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ahorros  $ahorros
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ahorros $ahorros)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ahorros  $ahorros
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ahorros $ahorros)
    {
        //
    }

    public function traerAhorros($usuario, $tipo){
        $sql = Ahorros::where([
            ['fk_id_usuario', $usuario],
            ['tipo', $tipo]
        ])->orderBy("created_at", "ASC")->get();

        return $sql;
    }

    public function agregarAhorro(Request $request){
        $objetivo = str_replace('.', '', $request->objetivo);
		$intervalo = isset($request->intervalo) ? str_replace('.', '', $request->intervalo) : NULL;
        $monto = 0;
		$montoDef = 0; 
        $resp['success'] = false;

        DB::beginTransaction();
        
        $cantidadAhorro = Ahorros::where([
            ['fk_id_usuario', $request->fk_id_usuario],
            ['tipo', 1]
        ])->count();

        if ($cantidadAhorro < 3) {
            $ahorro = new Ahorros;
            $ahorro->tipo = 1;
            $ahorro->fk_id_usuario = $request->fk_id_usuario;
            $ahorro->nombre = $request->nombreAhorro;
            $ahorro->objetivo = $objetivo;
            $ahorro->ahorrado = '0';
            $ahorro->intervalo = $intervalo;
            $ahorro->tipo_ahorro = $request->tipoAhorro;
            $ahorro->fechaMeta = isset($request->fechaMeta) ? date("Y-m-d", strtotime(str_replace('/', '-', $request->fechaMeta))) : NULL;

            if ($ahorro->save()) {
                if($request->tipoAhorro == 2){
                    while ($montoDef < $objetivo) {
                        $monto +=  $intervalo;
                        $montoDef += $monto;
                        
                        $montos = new Montos;
                        $montos->fk_id_ahorro = $ahorro->id;
                        $montos->valor = $monto;
                        $montos->chec = 0;

                        $montos->save();
                    }
                }

                DB::commit();
                $resp["success"] = true;
				$resp['msj'] = "Se ha registrado correctamente";
            } else {
                DB::rollback();
                $resp['msj'] = "No se ha creado el ahorro";
            }
            
        } else {
            $resp['msj'] = "Ya tienes tres ahorros creados";
        }

        return $resp;
    }

    public function borrarAhorro(Request $request){
        $resp["success"] = false;

        DB::beginTransaction();

        $montos = Montos::where('fk_id_ahorro', $request->idAhorro);

        if ($montos->count() > 0) {
            if ($montos->delete()) {
                $ahorro = Ahorros::where('id', $request->idAhorro)->delete();
                if ($ahorro) {
                    DB::commit();
                    $resp["success"] = true;
                    $resp["msj"] = "Se ha eliminado correctamente";
                }else{
                    DB::rollback();
                    $resp['msj'] = "No se ha eliminado el ahorro";
                }
            } else {
                DB::rollback();
                $resp['msj'] = "No se ha eliminado el ahorro";
            }
        } else {
            $ahorro = Ahorros::where('id', $request->idAhorro)->delete();
                
            if ($ahorro) {
                DB::commit();
                $resp["success"] = true;
                $resp["msj"] = "Se ha eliminado correctamente";
            }else{
                DB::rollback();
                $resp['msj'] = "No se ha eliminado el ahorro";
            }
        }

        return $resp;

    }

    public function agregarDeuda(Request $request){
        $resp["success"] = false;
        $deuda = new Ahorros;

        $deuda->fk_id_usuario = $request->fk_id_usuario;
        $deuda->nombre = $request->nombre;
        $deuda->objetivo = str_replace('.', '', $request->objetivo);
        $deuda->ahorrado = '0';
        $deuda->tipo_ahorro = 1;
        $deuda->fechaMeta = isset($request->formfechaLimite) ? date("Y-m-d", strtotime(str_replace('/', '-', $_POST["formfechaLimite"]))) : NULL;
        $deuda->tipo = 2;

        DB::beginTransaction();

        if ($deuda->save()) {
            DB::commit();
            $resp["success"] = true;
		    $resp['msj'] = "Se ha registrado correctamente";
        } else {
            DB::rollback();
            $resp["msj"] = "No se ha registrado";
        }

        return $resp;
        
    }

    public function datosAhorro($idAhorro){
        $ahorro = Ahorros::find($idAhorro);

        return $ahorro;
    }
}
