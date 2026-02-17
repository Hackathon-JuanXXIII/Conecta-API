<?php

namespace App\Http\Controllers;

use App\Models\LogrosUsers;
use Illuminate\Http\Request;

class LogrosUsersController extends Controller
{
    protected int $max_paginate = 10;

    /**
     * Mostrar todos los Logros de los Usuarios
     * 
     * @return \Illuminate\Http\JsonResponse Lista de todos los Logros de los Usuarios
     */
    public function index(){
        $logros = LogrosUsers::with('logro')->paginate($this->max_paginate);
        return response()->json($logros);
    }

    /**
     * Mostrar un Logro por Id
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse Detalles del Logro
     */
    public function show($id){
        $logro = LogrosUsers::with('logro')->findOrFail($id);
        return response()->json($logro);
    }

    /**
     * Crear un nuevo Logro de Usuario
     * 
     * @param Request $request Datos del nuevo Logro de Usuario
     * @return \Illuminate\Http\JsonResponse Detalles del Logro de Usuario creado
     */
    public function store(Request $request){
        $data = $request->all();
        $logro = LogrosUsers::create($data);
        return response()->json($logro, 201);
    }

    /**
     * Actualizar un Logro de Usuario existente
     * 
     * @param Request $request Datos actualizados del Logro de Usuario
     * @param int $id ID del Logro de Usuario a actualizar
     * @return \Illuminate\Http\JsonResponse Detalles del Logro de Usuario actualizado
     */
    public function update(Request $request, $id){
        $logro = LogrosUsers::getLogroUserById($id);
        $data = $request->all();
        $logro->update($data);
        return response()->json($logro);
    }

    /**
     * Eliminar un Logro de Usuario
     * 
     * @param int $id ID del Logro de Usuario a eliminar
     * @return \Illuminate\Http\JsonResponse Mensaje de éxito
     */
    public function destroy($id){
        $logro = LogrosUsers::getLogroUserById($id);
        $logro->delete();
        return response()->json(['message' => 'Logro de Usuario eliminado con éxito']);
    }

    public function getLogrosByUserId($userId){
        $logros = LogrosUsers::getLogrosByUserId($userId);
        return response()->json($logros);
    }
}
