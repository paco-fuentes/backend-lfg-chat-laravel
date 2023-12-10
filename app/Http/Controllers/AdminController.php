<?php

namespace App\Http\Controllers;

use App\Models\PartyRoom;
use App\Models\User;
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
                "title" => "string|max:100|unique:videogames,title," . $id,
                "year" => "string|max:10",
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

    public function createVideogame(Request $request)
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

            $validator = Validator::make($request->all(), [
                "title" => "required|string|max:100|unique:videogames",
                "year" => "required|string|max:10",
                "img_url" => "required|string|max:750",
                "genre" => "string|in:Unknown,Action,Adventure,RPG,FPS,Platformer",
                "is_active" => "boolean"
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Error creating videogame",
                        "errors" => $validator->errors()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $newVideogame = Videogames::create(
                [
                    "title" => $request->input("title"),
                    "year" => $request->input("year"),
                    "img_url" => $request->input("img_url"),
                    "genre" => $request->input("genre"),
                    "is_active" => $request->input("is_active"),
                ]
            );

            return response()->json(
                [
                    "success" => true,
                    "message" => "Videogame created successfully",
                    "data" => $newVideogame
                ],
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error creating videogame",

                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function deleteVideogame(Request $request, $id)
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

            if ($videogame) {
                Videogames::destroy($id);

                return response()->json(
                    [
                        "success" => true,
                        "message" => "Videogame deleted successfully"
                    ],
                    Response::HTTP_OK
                );
            }

            return response()->json(
                [
                    "success" => false,
                    "message" => "Videogame not found"
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error deleting videogame",

                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getAllUsers(Request $request)
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

            $users = User::query()->get();

            if ($users->isEmpty()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "No users found"
                    ],
                    Response::HTTP_NO_CONTENT
                );
            }

            return response()->json(
                [
                    "success" => true,
                    "message" => "Users found",
                    "data" => $users
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error getting users",
                    "data" => $users

                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getAllPartyRoom(Request $request)
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

            $partyRooms = PartyRoom::query()->get();

            if ($partyRooms->isEmpty()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "No party rooms found"
                    ],
                    Response::HTTP_NO_CONTENT
                );
            }

            return response()->json(
                [
                    "success" => true,
                    "message" => "Party rooms found",
                    "data" => $partyRooms
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error getting party rooms",
                    "data" => $partyRooms

                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
