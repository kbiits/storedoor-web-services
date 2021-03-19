<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update($id, Request $request)
    {

        $validatedData = $request->validate([
            "username" => "bail|required|min:6",
            "email" => "bail|required|email",
            "username" => "bail|alpha_num",
            "fullname" => "bail|required|regex:/^[\pL\s\-]+$/u"
            // "password" => "bail|required|min:8"
        ]);

        $user = User::find($id);

        if ($user) {
            if ($request->password != null) {
                $isUpdate = $user->update($request->all());
            } else {
                $isUpdate = $user->update($validatedData);
            }
            if ($isUpdate) {
                return response()->json([
                    "is_error" => false,
                    "data" => [
                        "user" => $user,
                    ],
                    "message" => "Berhasil memperbarui data user",
                ], 200);
            }
            return response()->json([
                "is_error" => true,
                "data" => null,
                "message" => "Gagal memperbarui data",
            ], 400);
        }
        return response()->json([
            "is_error" => true,
            "message" => "Tidak dapat menemukan akun anda"
        ], 400);
    }

    public function profilePictureUpload($id, Request $request)
    {
        $request->validate([
            "image" => 'required'
        ]);

        $user = User::find($id);
        if ($user) {
            // $user->avatar = asset('user_profile_pict') . "/" . $imageName;
            // $user->save();
            $isUpdated = $user->update([
                "avatar" => $request->image,
            ]);
            if ($isUpdated) {
                return response()->json([
                    "message" => "Berhasil memperbarui foto profil",
                    "is_error" => false,
                    "avatar" => $user->avatar,
                ], 200);
            }
            return response()->json([
                "message" => "Gagal memperbarui foto profil",
                "is_error" => true,
                "reason" => "User tidak dapat ditemukan",
            ], 404);
        }

        return response()->json([
            "message" => "Gagal memperbarui foto profil",
            "is_error" => true,
        ], 400);
    }
}
