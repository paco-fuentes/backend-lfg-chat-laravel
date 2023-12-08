<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = $this->validateRegister($request);
            if ($validator->fails()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => $validator->errors()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

            $newUser = User::create([
                "username" => $request->input("username"),
                "email" => $request->input("email"),
                "password" => bcrypt($request->input("password")),

            ]);

            return response()->json(
                [
                    "success" => true,
                    "message" => "User created successfully",
                    "data" => $newUser
                ],
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error creating user"
                ],

                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    private function validateRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "username" => "required|min:3|max:70",
            "email" => "required|email",
            "password" => "required|min:8|max:200|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/",

        ]);
        return $validator;
    }
}
