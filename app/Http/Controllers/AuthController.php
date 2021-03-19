<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\Cart;
use App\Models\FavoriteProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $oldUser = User::where('email', $request->email)->orWhere('username', $request->username)->first();
        if ($oldUser) {
            return response()->json([
                "data" => null,
                "errors" => [
                    "Email atau Username telah digunakan",
                ],
                "is_error" => true,
                "message" => "Gagal didaftarkan",
            ], 409);
        }

        $user = User::create([
            "username" => $request->username,
            "email" => $request->email,
            "password" => $request->password,
            "fullname" => $request->fullname,
        ]);



        if ($user) {
            //  Create cart for user (empty cart, just put the user_id) to prevent error while displaying cart list in flutter app
            Cart::create([
                'user_id' => $user->id,
                'product_id' => null,
            ]);

            // Create favorite for user (empty favorite, just put the user_id) to prevent error while displaying favorite list in flutter app
            FavoriteProduct::create([
                'user_id' => $user->id,
                'product_id' => null,
            ]);


            return response()->json([
                "data" => [
                    "user" => [
                        "id" => $user->id,
                        "email" => $user->email,
                        "username" => $user->username,
                        "fullname" => $user->fullname,
                    ]
                ],
                "errors" => null,
                "is_error" => false,
                "message" => "Berhasil didaftarkan",
            ], 200);
        }

        return response()->json([
            "data" => null,
            "errors" => "Unknown Error",
            "is_error" => true,
            "message" => "Terjadi kesalahan",
        ], 400);
    }

    // public function getNewToken($accountData, $password, $deviceName)
    // {
    //     $user = User::where('email', $accountData)->orWhere('username', $accountData)->first();

    //     // Check credentials
    //     if (!$user || !Hash::check($password, $user->password)) {
    //         return null;
    //     }
    //     $user->tokens()->where('name', $deviceName)->delete();
    //     return $user->createToken($deviceName)->plainTextToken;
    // }

    public function login(UserLoginRequest $request)
    {
        $user = User::where('email', $request->account_data)->orWhere('username', $request->account_data)->first();
        if ($user && Hash::check($request->password, $user->password)) {

            $token = null;

            // if (!$user->tokens()->where('name', $request->device_name)->first()) {
            // }

            // Create token in every login
            $user->tokens()->where('name', $request->device_name)->delete();
            $token = $user->createToken($request->device_name)->plainTextToken;

            return response()->json([
                "data" => [
                    "user" => [
                        "email" => $user->email,
                        "id" => $user->id,
                        "username" => $user->username,
                        "avatar" => $user->avatar,
                        "fullname" => $user->fullname,
                    ]
                ],
                "errors" => null,
                "is_error" => false,
                "message" => "Berhasil login",
                "token" => $token,
            ], 200);
        }

        return response()->json([
            "data" => null,
            "errors" => "Password salah",
            "is_error" => true,
            "message" => "Gagal login"
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            "message" => "Logged out"
        ]);
    }
}
