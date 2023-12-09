<?php

namespace App\Http\Controllers;

use App\Models\Videogames;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function updateVideogame(Request $request, $id)
    {
        try {
            $user = auth()->user();

            if ($user->role != "admin") {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "You are not admin"
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $videogame = Videogames::query()->find($id);
            $title = $request->input("title");
            $year = $request->input("year");
            $img_url = $request->input("img_url");
            $genre = $request->input("genre");
            $is_active = $request->input("is_active");
            $validator = Validator::make($request->all(), [
                "title" => "required|string|max:100|unique:videogames,title," . $id,
                "year" => "required|string|max:10",
                "img_url" => "string|max:750",
                "genre" => "string|in:Unknown,Action,Adventure,RPG,FPS,Platformer",
                "is_active" => "boolean"
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Error updating videogame",
                        "errors" => $validator->errors()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            if ($request->has("title")) {
                $videogame->title = $title;
            }
            if ($request->has("year")) {
                $videogame->year = $year;
            }
            if ($request->has("img_url")) {
                $videogame->img_url = $img_url;
            }
            if ($request->has("genre")) {
                $videogame->genre = $genre;
            }
            if ($request->has("is_active")) {
                $videogame->is_active = $is_active;
            }

            $videogame->save();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Videogame updated successfully",
                    "data" => $videogame
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error updating videogame",

                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
