<?php
//faltan importaciones de los controladores una vez creados

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
//RUTA DE COMPROBACION
Route::get('/api', function (Request $request) {
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
Route::group([
'middleware' =>['auth: sanctum']
], function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

//CRUD VIDEOGAMES
// Route::group([
// 'middleware' => ['auth: sanctum']    ], function() {
    Route::post('/create', [VideogamesController::class, 'createParties']);
    Route::get('/videogames', [VideogamesController::class, 'getAllGames']);
    Route::put('/update/{gameId}', [GamesController::class, 'update'], function($gameId){
        return 'Games'.$gameId;
    });
    Route::delete('/delete/{gameId}',[GamesController::class, 'delete'], function($gameId){
        return 'Games'.$gameId;
    });
// }
// );

//CRUD PARTIES
Route::group([
'middleware' => ['auth: sanctum']
], function(){
    Route::post('/join/{gameId}', [PartiesController::class,'join'], function($gameId){
        return 'Games'.$gameId;
    });
    Route::post('/leave/{gameId}', [PartiesContoller::class, 'leave'], function($gameId){
        return 'Games'.$gameId;
    });
});

//CRUD MESSAGE
Route::group([
    'middleware' => ['auth: sanctum']
], function(){
    Route::post('/send-message/{gameId}', [MessageContoller::class, 'send-message'], function($gameId){
        return 'Games'.$gameId;
    });
    //visualizar todos los mensajes en una partida
    Route::get('/view-message/{gameId}',[MessageContoller::class,'allMessageParties'], function($gameId){
        return 'Games'.$gameId;
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
   return $request->user();
});
