<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Sanctum\PersonalAccessToken;

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
            "password" => "required|min:8|max:80|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/",

        ]);
        return $validator;
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    "email" => "required|email",
                    "password" => "required|min:8|max:80|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/",
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Error login",
                        "error" => $validator->errors()
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $email = $request->input("email");
            $password = $request->input("password");
            $user = User::query()->where("email", $email)->first();

            if (!$user || !Hash::check($password, $user->password)) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Email or password incorrect"
                    ],
                    Response::HTTP_UNAUTHORIZED
                );
            }
            $token = $user->createToken("apiToken")->plainTextToken;

            return response()->json(
                [
                    "success" => true,
                    "message" => "Login successfully",
                    "token" => $token,
                    "data" => $user,
                ]
            );
        } catch (\Throwable $th) {
            log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error login",

                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function profile(Request $request)
    {
        try {
            $user = auth()->user();

            return response()->json(
                [
                    "success" => true,
                    "message" => "User profile",
                    "data" => $user,
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error getting profile",
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function logout(Request $request)
    {
        try {
            $accesToken = $request->bearerToken();
            $token = PersonalAccessToken::findToken($accesToken);
            $token->delete();

            return response()->json(
                [
                    "success" => true,
                    "message" => "User logout",
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error logout",
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $token = auth()->user();
            $user = User::query()->find($token->id);

            $name = $request->input("username");
            $email = $request->input("email");
            $password = $request->input("password");

            if ($request->has("username")) {
                $user->username = $name;
            }
            if ($request->has("email")) {
                $user->email = $email;
            }
            if ($request->has("password")) {
                $user->password = bcrypt($password);
            }

            $user->save();

            return response()->json(
                [
                    "success" => true,
                    "message" => "User updated successfully",
                    "data" => $user
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json(
                [
                    "success" => false,
                    "message" => "Error updating user profile"
                ],

                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
