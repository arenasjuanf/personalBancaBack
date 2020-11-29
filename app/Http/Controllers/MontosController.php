<?php

namespace App\Http\Controllers;

use App\Models\Montos;
use App\Models\Ahorros;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MontosController extends Controller
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
     * @param  \App\Models\Montos  $montos
     * @return \Illuminate\Http\Response
     */
    public function show(Montos $montos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Montos  $montos
     * @return \Illuminate\Http\Response
     */
    public function edit(Montos $montos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Montos  $montos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Montos $montos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Montos  $montos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Montos $montos)
    {
        //
    }

    public function datosMontos($idAhorro){
        $montos = Montos::where("fk_id_ahorro", $idAhorro)->orderBy('valor','asc')->get();

        return $montos;
    }

    public function agregarMonto(Request $request) {
        $resp["success"] = false;
        $ahorrado = 0;

        $ahorro = Ahorros::find($request->idAhorro);

        if (!empty($ahorro)) {
            DB::beginTransaction();

            $ahorrado = $ahorro->ahorrado + str_replace('.', '', $_POST["monto"]);

            $monto = new Montos;
            $monto->fk_id_ahorro = $request->idAhorro;
            $monto->valor = str_replace('.', '', $request->monto);

            if ($monto->save()) {
                
                $ahorro->ahorrado = $ahorrado;

                if ($ahorro->save()) {
                    DB::commit();
                    $resp["success"] = true;
                    $resp['msj'] = "Se ha registrado correctamente";
                } else {
                    DB::rollback();
                    $resp['msj'] = "Error al actualizar el ahorro";
                }
            } else {
                DB::rollback();
                $resp['msj'] = "No se ha guardado el monto";
            }
        } else {
            $resp["msj"] = "Ahorros no encontrado";
        }

        return $resp;
        
    }

    public function actualizarMonto(Request $request){
        
        $resp["success"] = false;

        DB::beginTransaction();
        
        $monto = Montos::find($request->id);

        $monto->chec = $request->chec;

        if ($monto->save()) {
            
            $ahorro = Ahorros::find($request->idAhorro);

            $ahorro->ahorrado = $request->ahorrado;

            if ($ahorro->save()) {
                DB::commit();
                $resp["success"] = true;
                $resp["msj"] = "Se ha actualizado correctamente";
            } else {
                DB::rollback();
                $resp["msj"] = "No se ha actualizado el ahorro";
            }
        } else {
            DB::rollback();
            $resp["msj"] = "No se ha actualizado el monto";
        }

        return $resp;
    }
}
