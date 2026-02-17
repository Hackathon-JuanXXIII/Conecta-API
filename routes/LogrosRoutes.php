<?php

use App\Http\Controllers\LogrosController;
use Illuminate\Support\Facades\Route;


// LOGROS
// -----------------------------------------------------------------------------------------
Route::prefix('/logros')->group(function(){
    Route::get('', [LogrosController::class, 'index']);   
});

Route::prefix('/logro')->group(function(){
    Route::get('/{id}', [LogrosController::class, 'show'])->where('id', '[0-9]+');
    Route::post('', [LogrosController::class, 'store']);
    Route::delete('/{id}', [LogrosController::class, 'destroy']);
    Route::put('/{id}', [LogrosController::class, 'update']);
});