<?php

namespace App\Http\Controllers;

use App\Models\ahorros_compartidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AhorrosCompartidosController extends Controller
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
     * @param  \App\Models\ahorros_compartidos  $ahorros_compartidos
     * @return \Illuminate\Http\Response
     */
    public function show(ahorros_compartidos $ahorros_compartidos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ahorros_compartidos  $ahorros_compartidos
     * @return \Illuminate\Http\Response
     */
    public function edit(ahorros_compartidos $ahorros_compartidos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ahorros_compartidos  $ahorros_compartidos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ahorros_compartidos $ahorros_compartidos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ahorros_compartidos  $ahorros_compartidos
     * @return \Illuminate\Http\Response
     */
    public function destroy(ahorros_compartidos $ahorros_compartidos)
    {
        //
    }



    public function compartirAhorro(Request $request){
        
        DB::beginTransaction();
        
        if($request->remove){
            $toRemove = ahorros_compartidos::where('fk_id_ahorro', $request->savingId)
            ->where('fk_id_usuario', $request->userId);

            if($toRemove->delete()){
                DB::commit();
                return array('success' => true, 'msj' => 'Contacto eliminado de ahorro compartido');
            }else{
                DB::rollback();
                return array('success' => false, 'msj' => 'Error al quitar contacto');
            }
            
            
        }else{
            
            $compartido = new ahorros_compartidos;
            $compartido->fk_id_ahorro = $request->savingId;
            $compartido->fk_id_usuario = $request->userId;

            if($compartido->save()){
                DB::commit();
                return array('success' => true, 'msj' => 'Ahorro compartido exitosamente');
            }else{
                DB::rollback();
                return array('success' => false, 'msj' => 'Error al compartir item');
            }
        }


    }


    public function listarUsuarios($ahorroId){

        $users = DB::select(DB::raw("SELECT * FROM ahorros_compartidos where fk_id_ahorro = {$ahorroId}"));
        return $users;
    }

}
