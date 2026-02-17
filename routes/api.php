<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\EventosController;

Route::prefix('v1')->group(function () {
    // Rutas públicas sin autentificación
    Route::post('/registro', [AuthController::class, 'registro']);
    Route::post('/login', [AuthController::class, 'login']);

    // Eventos para el mini-dashboard web
    Route::get('/eventosweb', [EventosController::class, 'indexweb']);

    
    //Rutas protegidas con Sanctum (dentro del grupo de la versión)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
        require __DIR__.'/UserRoutes.php';          // Rutas de Users
        require __DIR__.'/RolesRoutes.php';         // Rutas de Roles
        require __DIR__.'/TagsRoutes.php';          // Rutas de Tags
        require __DIR__.'/CategoriaRoutes.php';     // Rutas de Categorías
        require __DIR__.'/EntidadesRoutes.php';     // Rutas de Entidades
        require __DIR__.'/EventosRoutes.php';       // Rutas de Eventos
        require __DIR__.'/AplicacionesRoutes.php';  // Rutas de Aplicaciones
        require __DIR__.'/LogrosUsersRoutes.php';   // Rutas de Logros de Usuarios
        require __DIR__.'/LogrosRoutes.php';        // Rutas de Logros
        });
});

