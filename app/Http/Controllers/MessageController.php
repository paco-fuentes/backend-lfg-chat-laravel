<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\PartyMember;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    public function createMessage(Request $request)
    {
        try {
            $user = $request->user();
            $partyRoom_id = $request->input('party_id');
            $partyMember = PartyMember::query()->where('user_id', $user->id)->where('party_id', $partyRoom_id)->first();

            if (!$partyMember) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "You are not a member of this chat"
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }
            $message = Message::query()->create([
                'message' => $request->input('message'),
                'user_id' => $user->id,
                'party_id' => $partyRoom_id,
            ]);

            return response()->json(
                [
                    "success" => true,
                    "message" => "Message created",
                    "data" => $message
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(
                [
                    "success" => false,
                    "message" => "Message not created"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function chatRoom(Request $request)
    {
        try {
            $user = auth()->user()->id;
            $partyRoom_id = $request->input('party_id');
            $partyMember = PartyMember::query()->where("user_id", $user)->where("party_id", $partyRoom_id)->first();

            if (!$partyMember) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "You are not a member of this chat"
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }

            $chatRoom = Message::query()->where("party_id", $partyRoom_id)->get();

            if ($chatRoom->isEmpty()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "This chat is empty"
                    ],
                    Response::HTTP_OK
                );
            }
            return response()->json(
                [
                    "success" => true,
                    "message" => "Chat obtained",
                    "data" => $chatRoom
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(
                [
                    "success" => false,
                    "message" => "Error getting chat"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function deleteMessage(Request $request, $id)
    {
        try {
            $userId = auth()->user()->id;

            $message = Message::query()
                ->where('id', $id)
                ->where('user_id', $userId)
                ->first();

            $message->delete();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Message deleted"
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Message not deleted"
                ],
                Response::HTTP_NOT_FOUND
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(
                [
                    "success" => false,
                    "message" => "Message not deleted"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function updateMessage(Request $request)
    {
        try {
            $user = auth()->user();
            $party_id = $request->input('party_id');
            $messageId = $request->input('message_id'); // Nuevo campo para el ID del mensaje
            $newMessage = $request->input('newMessage');

            $message = Message::query()
                ->where("id", $messageId)
                ->where("user_id", $user->id)
                ->where("party_id", $party_id)
                ->first();

            if (!$message) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Message not found"
                    ],
                    Response::HTTP_NOT_FOUND
                );
            }

            if ($request->has("newMessage")) {
                $message->message = $newMessage;
            }

            $message->save();

            return response()->json(
                [
                    "success" => true,
                    "message" => "Message updated",
                    "data" => $message
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json(
                [
                    "success" => false,
                    "message" => "Message not updated"
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
