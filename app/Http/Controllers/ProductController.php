<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{

    // Get all product
    public function index()
    {
        $products = Product::all();
        if ($products) {
            // Success
            return response()->json([
                "data" => [
                    "products" => $products
                ],
                "message" => "success",
                "is_error" => false,
            ], 200);
        }
        // Failed
        return response()->json([
            "data" => [
                "products" => null
            ],
            "message" => "Gagal mendapatkan data products",
            "is_error" => true,
        ], 400);
    }

    // Get all product of a user
    public function indexBasedOnUserId($id)
    {
        $products = Product::where('user_id', $id)->get();
        if ($products) {
            // Success
            return response()->json([
                "data" => [
                    "products" => $products,
                    "user_id" => $id,
                ],
                "message" => "success",
                "is_error" => false,
            ], 200);
        }

        // Failed
        return response()->json([
            "data" => [
                "products" => null,
            ],
            "message" => "Gagal mendapatkan data products",
            "is_error" => true,
        ], 404);
    }

    // Adding product and determine who add that product by user_id
    public function store($id, Request $request)
    {
        $request->validate([
            "img" => 'required',
            "title" => "required",
            "description" => "required",
            "price" => "required",
            "category_id" => "required|numeric",
        ]);


        $product = Product::create([
            'title' => $request->title,
            'description' => $request->description,
            'img' => $request->img,
            'price' => $request->price,
            'category_id' => (int) $request->category_id,
            'user_id' => (int) $id,
            'rating' => $request->rating ?? 3.0,
        ]);

        if ($product) {
            // Success
            return response()->json([
                "message" => "success",
                "data" => [
                    "product" => $product,
                    "user_id" => (int) $id
                ],
                "is_error" => false,
            ], 200);
        } else {
            // Failed
            return response()->json([
                "message" => "Gagal menambahkan product, harap coba lagi",
                "data" => [
                    "product" => null,
                ],
                "is_error" => true,
            ], 422);
        }
    }

    public function destroy($id, $productId)
    {
        $product = Product::where('id', $productId)->where('user_id', $id)->first();

        if ($product) {
            $isDeleted = $product->delete();
            if ($isDeleted) {
                // Success
                return response()->json([
                    "message" => "successfully deleted",
                    "is_error" => false,
                ], 200);
            }
            // Failed
            return response()->json([
                "message" => "Gagal menghapus data, harap coba lagi",
                "is_error" => true,
            ], 400);
        }

        // Failed
        return response()->json([
            "message" => "Data product yang ingin dihapus tidak ditemukan",
            "reason" => "product or user doesn't found",
            "is_error" => true,
        ], 404);
    }

    public function update(Request $request, $id, $productId)
    {
        if (empty($request->all())) {
            // Failed
            return response()->json([
                "message" => "Harap isi field yang ada untuk memperbarui data product",
                "reason" => "Can't process data, Data not found",
                "is_error" => true,
            ], 422);
        }

        $product = Product::where('id', $productId)->where('user_id', $id)->first();
        if ($product) {
            $imgString = $request->img;
            $isUpdated = null;
            if (is_null($imgString)) {
                $isUpdated = $product->update([
                    "title" => $request->title,
                    "description" => $request->description,
                    // use old data of img
                    "img" => $product->img,
                    "price" => $request->price,
                    "category_id" => $request->category_id,
                    "user_id" => $id,
                ]);
            } else {
                $isUpdated = $product->update([
                    "title" => $request->title,
                    "img" => $request->img,
                    "description" => $request->description,
                    "price" => $request->price,
                    "category_id" => $request->category_id,
                    "user_id" => $id,
                ]);
            }

            if ($isUpdated) {
                // Success
                return response()->json([
                    "data" => [
                        "product" => $product,
                        "user_id" => $id
                    ],
                    "is_updated" => $isUpdated,
                    "message" => "success",
                    "is_error" => false,
                ], 200);
            } else {
                // Failed
                return response()->json([
                    "data" => [
                        "old_product" => $product,
                        "user_id" => $id
                    ],
                    "is_updated" => $isUpdated,
                    "message" => "Gagal memperbarui data product",
                    "is_error" => true,
                ], 400);
            }
        }

        // Failed
        return response()->json([
            "message" => "Data product tidak dapat ditemukan, harap coba lagi",
            "reason" => "product or user doesn't found",
            "is_error" => true,
        ], 404);
    }
}
