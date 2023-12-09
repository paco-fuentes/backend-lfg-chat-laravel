<?php
//faltan importaciones de los controladores una vez creados

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideogamesController;
use App\Http\Middleware\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//RUTA DE COMPROBACION
Route::get('/', function (Request $request) {
    return response()->json(
        [
            "success" => true,
            "message" => "Healthcheck ok"
        ],
        Response::HTTP_OK
    );
});

//CRUD USERS

// AUTH
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// Route::group([
// 'middleware' =>['auth: sanctum']
// ], 
// function() {
    Route::middleware("auth:sanctum")->get('/profile', [UserController::class, 'profile']);
    Route::middleware("auth:sanctum")->post('/logout', [UserController::class, 'logout']);
    Route::middleware("auth:sanctum")->put('/update', [UserController::class, 'updateProfile']);
    Route::middleware("auth:sanctum")->delete('/user', [UserController::class, 'deleteUser']);
// }
// );

//CRUD VIDEOGAMES

    Route::get('/videogames', [VideogamesController::class, 'getAllGames']);
    Route::get('/videogames/{id}', [VideogamesController::class, 'getGameById']);
    Route::middleware("auth:sanctum","admin")->put('/videogames/{id}', [AdminController::class, 'updateVideogame']);
    Route::middleware("auth:sanctum","admin")->post('/videogame', [AdminController::class, 'createVideogame']);
    Route::middleware("auth:sanctum","admin")->delete('/videogame/{id}', [AdminController::class, 'deleteVideogame']);
    Route::middleware("auth:sanctum","admin")->get('/users', [AdminController::class, 'getAllUsers']);

