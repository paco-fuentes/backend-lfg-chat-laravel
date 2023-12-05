<?php

namespace App\Http\Controllers;

use App\Models\Videogames;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VideogamesController extends Controller
{
    public function getAllGames(Request $request)
    {
       try {
        $videogames=Videogames::query()->where('is_active', true)->get();
        if($videogames->isEmpty()){
            return response()->json(
                [
                    'success' => false,
                    'message' => 'No videogames found'
                ],
                Response::HTTP_NOT_FOUND
            );
        }
        return response()->json(
            [
                'success' => true,
                'message' => 'Videogames found',
                'data' => $videogames
            ],
            Response::HTTP_OK
        );
       } catch (\Throwable $th) {
        Log::error($th->getMessage());
        
        return response()->json(
            [
                'success' => false,
                'message' => 'Error founding games'
            ],
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    
    }
}
}
