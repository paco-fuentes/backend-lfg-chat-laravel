<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//CRUD USERS

// AUTH
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/profile', [AuthController::class, 'profile']);
Route::post('/logout', [AuthController::class, 'logout']);

//CRUD GAMES
Route::post('/create', [GamesController::class, 'createParties']);
Route::get('/search', [GamesController::class, 'searchParties']);
Route::put('/update/{gameId}', [GamesController::class, 'update']);
Route::delete('/delete/{gameId}',[GamesController::class, 'delete']);

//CRUD PARTIES
Route::post('/join/{gameId}', [PartiesController::class,'join']);
Route::post('/leave/{gameId}', [PartiesContoller::class, 'leave']);

//CRUD MESSAGE
Route::post('/send-message/{gameId}', [MessageContoller::class, 'send-message']);
//visualizar todos los mensajes en una partida
Route::get('/view-message/{gameId}',[MessageContoller::class,'allMessageParties']);










Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
