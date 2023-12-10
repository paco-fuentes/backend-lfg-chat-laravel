<?php

namespace App\Http\Controllers;

use App\Models\PartyMember;
use App\Models\PartyRoom;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PartyRoomController extends Controller
{
    public function createPartyRoom(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Authentication required"
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $validator = Validator::make($request->all(), [
                "room_name" => "required|string|max:100",
                "videogame_id" => "required|exists:videogames,id",
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Error creating partyroom",
                        "errors" => $validator->errors()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $newPartyRoom = PartyRoom::create([
                "room_name" => $request->input("room_name"),
                "admin_id" => $user->id,
                "videogame_id" => $request->input("videogame_id"),
            ]);

            $newPartyRoom->admins()->attach($user->id, ['is_active' => true]);

            return response()->json(
                [
                    "success" => true,
                    "message" => "PartyRoom created successfully",
                    "data" => $newPartyRoom
                ],
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error creating partyroom",
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function getPartyByVideogameId($videogame_id)
    {
        try {
            $partyRooms = PartyRoom::where('videogame_id', $videogame_id)->get();

            return response()->json(
                [
                    "success" => true,
                    "message" => "PartyRooms retrieved successfully",
                    "data" => $partyRooms
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error retrieving partyrooms",
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function deletePartyRoom(Request $request, $id)
    {
        try {
            $user = auth()->user();
            $partyRoom = PartyRoom::query()->find($id);
            $userPartyRoom = PartyMember::query()->where("party_id", $id)->first();

            if (!$partyRoom) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "PartyRoom not found"
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }

            if ($userPartyRoom->user_id === $user->id) {
                $partyRoom->party_memberManyToMany()->detach($user->id);

                PartyRoom::destroy($id);

                return response()->json(
                    [
                        "success" => true,
                        "message" => "PartyRoom deleted successfully"
                    ],
                    Response::HTTP_OK
                );
            } else {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "You are not admin to this partyroom"
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error deleting partyroom",
                    "data" => $userPartyRoom

                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
           

