<?php

use App\Http\Controllers\LogrosUsersController;
use Illuminate\Support\Facades\Route;


// LOGROS
// -----------------------------------------------------------------------------------------
Route::prefix('/logros/usuarios')->group(function(){
    Route::get('', [LogrosUsersController::class, 'index']);   
});

Route::prefix('/logro/usuario')->group(function(){
    Route::get('/{userId}', [LogrosUsersController::class, 'getLogrosByUserId'])->where('userId', '[0-9]+');
    Route::get('detalles/{id}', [LogrosUsersController::class, 'show'])->where('id', '[0-9]+');
    Route::post('', [LogrosUsersController::class, 'store']);
    Route::delete('/{id}', [LogrosUsersController::class, 'destroy']);
    Route::put('/{id}', [LogrosUsersController::class, 'update']);
});