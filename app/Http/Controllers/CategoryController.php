<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        if ($categories != null) {
            return response()->json([
                "data" => [
                    "categories" => $categories,
                ],
                "is_error" => false,
            ], 200);
        }

        return response()->json([
            "data" => null,
            "is_error" => true,
            "message" => "Data tidak dapat ditemukan"
        ], 404);
    }

    public function store(Request $request)
    {
        $newCategory = Category::create([
            "slug" => $request->slug,
        ]);
        if ($newCategory) {
            return response()->json([
                "data" => [
                    "category" => $newCategory,
                ],
                "is_error" => false,
            ], 200);
        }

        return response()->json([
            "data" => null,
            "is_error" => true,
            "message" => "Gagal memproses data"
        ], 422);
    }

    public function destroy($categoryId)
    {
        $category = Category::destroy($categoryId);
        if ($category) {
            return response()->json([
                "message" => "Berhasil dihapus",
                "is_error" => false,
            ], 200);
        }

        return response()->json([
            "is_error" => true,
            "message" => "Tidak dapat menemukan kategori tersebut"
        ], 400);
    }

    public function update($categoryId, Request $request)
    {
        $category = Category::find($categoryId);
        if ($category) {
            $isUpdated = $category->update([
                "slug" => $request->slug,
            ]);

            if ($isUpdated) {
                return response()->json([
                    "message" => "Berhasil diperbarui",
                    "is_error" => false,
                    "data" => [
                        "category" => $category,
                    ],
                ], 200);
            }

            return response()->json([
                "message" => "Terjadi kesalahan saat memperbarui data",
                "is_error" => true,
            ], 400);
        }
        return response()->json([
            "is_error" => true,
            "message" => "Tidak dapat menemukan kategori"
        ], 404);
    }
}
