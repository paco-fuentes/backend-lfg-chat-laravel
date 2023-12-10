<?php

namespace App\Http\Controllers;

use App\Models\PartyMember;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PartyMemberController extends Controller
{
    public function joinParty(Request $request, $party_id)
    {
        try {
            $user = auth::user();
            if (!$user) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Authentication required"
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $existingMember = PartyMember::where('user_id', $user->id)->where('party_id', $party_id)->first();
            if ($existingMember) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "User is already a member of the party"
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $partyMember = PartyMember::create([
                'user_id' => $user->id,
                'party_id' => $party_id,
            ]);

            return response()->json(
                [
                    "success" => true,
                    "message" => "User joined the party successfully",
                    "data" => $partyMember
                ],
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error joining the party",
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function leaveParty(Request $request, $party_id)
    {
        try {
            $user = auth::user();
            if (!$user) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Authentication required"
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $partyMember = PartyMember::where('user_id', $user->id)->where('party_id', $party_id)->first();

            if ($partyMember) {
                $partyMember->delete();

                return response()->json(
                    [
                        "success" => true,
                        "message" => "User left the party successfully"
                    ],
                    Response::HTTP_OK
                );
            } else {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "User is not a member of the party"
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error leaving the party",
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
