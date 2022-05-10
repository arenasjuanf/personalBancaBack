<?php

namespace App\Http\Controllers;

use App\Models\Contactos;
use App\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ContactosController extends Controller
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
        
        // get user by id
        $foundContact = Usuarios::where('email', $request->email)->get();

        if(!$foundContact->isEmpty()){
            $datos = $foundContact[0];
            $contacto = new Contactos;
            $contacto->fk_id_user = $request->userId;
            $contacto->fk_id_contacto = $datos->id;
			DB::beginTransaction();

            if($contacto->save()){
                DB::commit();
                return array('success' => true, 'msj' => 'Contacto agregado correctamente');
            }else{
                DB::rollback();
                return array('success' => false, 'msj' => 'Error al agregar contacto');
            }

        }else{
            return array('success' => false, 'msj' => 'contacto no encontrado');
        }
    }

    /**
     * Display the specified contacts.
     *
     * @param  \App\Models\Contactos  $contactos
     * @return \Illuminate\Http\Response
     */
    public function show($userId)
    {
        $userContacts = DB::select(DB::raw("SELECT * FROM (
            SELECT 
                C.id as idContacto 
                ,U2.id AS idUser
                ,U2.email AS email
                ,CONCAT(U2.nombres, ' ', U2.apellidos) AS nombre
            FROM contactos C 
                LEFT JOIN usuarios U ON C.fk_id_user = U.id 
                LEFT JOIN usuarios U2 ON C.fk_id_contacto = U2.id
            WHERE C.fk_id_user = '{$userId}'
            UNION
            SELECT 
                C.id as idContacto 
                ,U2.id AS idUser
                ,U2.email AS email
                ,CONCAT(U2.nombres, ' ', U2.apellidos) AS nombre
            FROM contactos C 
                LEFT JOIN usuarios U ON C.fk_id_contacto = U.id 
                LEFT JOIN usuarios U2 ON C.fk_id_user = U2.id
            WHERE C.fk_id_contacto = '{$userId}') T
            GROUP BY T.idContacto, T.idUser, T.nombre, T.email"));

        return $userContacts;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contactos  $contactos
     * @return \Illuminate\Http\Response
     */
    public function edit(Contactos $contactos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contactos  $contactos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contactos $contactos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contactos  $contactos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contactos $contactos)
    {
        //
    }
}
