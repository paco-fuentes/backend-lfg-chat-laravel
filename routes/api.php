<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PartyMemberController;
use App\Http\Controllers\PartyRoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideogamesController;
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

// RUTA DE COMPROBACION
Route::get('/', function (Request $request) {
    return response()->json(
        [
            "success" => true,
            "message" => "Healthcheck ok"
        ],
        Response::HTTP_OK
    );
});

// PUBLIC
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// USER WITH AUTH
Route::middleware("auth:sanctum")->get('/profile', [UserController::class, 'profile']);
Route::middleware("auth:sanctum")->post('/logout', [UserController::class, 'logout']);
Route::middleware("auth:sanctum")->put('/update', [UserController::class, 'updateProfile']);
Route::middleware("auth:sanctum")->delete('/user', [UserController::class, 'deleteUser']);

// VIDEOGAMES PUBLIC
Route::get('/videogames', [VideogamesController::class, 'getAllGames']);
Route::get('/videogames/{id}', [VideogamesController::class, 'getGameById']);

// VIDEOGAMES WITH AUTH
Route::middleware("auth:sanctum", "admin")->put('/videogames/{id}', [AdminController::class, 'updateVideogame']);
Route::middleware("auth:sanctum", "admin")->post('/videogame', [AdminController::class, 'createVideogame']);
Route::middleware("auth:sanctum", "admin")->delete('/videogame/{id}', [AdminController::class, 'deleteVideogame']);
Route::middleware("auth:sanctum", "admin")->get('/users', [AdminController::class, 'getAllUsers']);

// PARTY ROOMS
Route::middleware("auth:sanctum")->post('/room', [PartyRoomController::class, 'createPartyRoom']);
Route::middleware("auth:sanctum")->get('/partygames/{videogame_id}', [PartyRoomController::class, 'getPartyByVideogameId']);
Route::middleware("auth:sanctum")->delete('/partymember', [PartyRoomController::class, 'deletePartyMember']);
Route::middleware("auth:sanctum", "admin")->get('/partyrooms', [AdminController::class, 'getAllPartyRoom']);

// PARTY MEMBERS
Route::middleware("auth:sanctum")->post('/partymembers/join/{party_id}', [PartyMemberController::class, 'joinParty']);
Route::middleware("auth:sanctum")->post('/partymembers/leave/{party_id}', [PartyMemberController::class, 'leaveParty']);

// MESSAGES
Route::middleware("auth:sanctum")->post('/message', [MessageController::class, 'createMessage']);
Route::middleware("auth:sanctum")->get('/message', [MessageController::class, 'chatRoom']);
Route::middleware("auth:sanctum")->delete('/message/{id}', [MessageController::class, 'deleteMessage']);
Route::middleware("auth:sanctum")->put('/message', [MessageController::class, 'updateMessage']);
