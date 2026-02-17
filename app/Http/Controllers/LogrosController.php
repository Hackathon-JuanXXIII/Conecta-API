<?php

namespace App\Http\Controllers;

use App\Models\Logros;
use Illuminate\Http\Request;

class LogrosController extends Controller
{
    protected int $max_paginate = 10;

    /**
     * Mostrar todos los Logros
     * 
     * @return \Illuminate\Http\JsonResponse Lista de todos los Logros
     */
    public function index(){
        $logros = Logros::paginate($this->max_paginate);
        return response()->json($logros);
    }

    /**
     * Mostrar un Logro por Id
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse Detalles del Logro
     */
    public function show($id){
        $logro = Logros::getLogroById($id);
        return response()->json($logro);
    }

    /**
     * Crear un nuevo Logro
     * 
     * @param Request $request Datos del nuevo Logro
     * @return \Illuminate\Http\JsonResponse Detalles del Logro creado
     */
    public function store(Request $request){
        $data = $request->all();
        $logro = Logros::create($data);
        return response()->json($logro, 201);
    }

    /**
     * Actualizar un Logro existente
     * 
     * @param Request $request Datos actualizados del Logro
     * @param int $id ID del Logro a actualizar
     * @return \Illuminate\Http\JsonResponse Detalles del Logro actualizado
     */
    public function update(Request $request, $id){
        $logro = Logros::getLogroById($id);
        $data = $request->all();
        $logro->update($data);
        return response()->json($logro);
    }

    /**
     * Eliminar un Logro
     * 
     * @param int $id ID del Logro a eliminar
     * @return \Illuminate\Http\JsonResponse Mensaje de éxito
     */
    public function destroy($id){
        $logro = Logros::getLogroById($id);
        $logro->delete();
        return response()->json(['message' => 'Logro eliminado con éxito']);
    }
}
