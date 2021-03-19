<?php

namespace App\Http\Controllers;

use App\Models\FavoriteProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteProductController extends Controller
{
    // Show all favorite products
    public function index($id, Request $request)
    {
        // $userId = $request->user_id;
        $productsId = FavoriteProduct::select('product_id')->where('user_id', $id)->get();
        if (empty($productsId)) {
            return response()->json([
                "data" => null,
                "is_error" => true,
                "message" => "User dengan id `$id` tidak ditemukan",
            ], 404);
        }

        $products = Product::whereIn('id', $productsId)->get();
        // if (!is_null($products)) {
        // if ($products->count() == 0) {
        //     return response()->json([
        //         "data" => [
        //             "products" => $products,
        //         ],
        //         "is_error" => false,
        //         "message" => "Success",
        //     ], 200);
        // }
        return response()->json([
            "data" => [
                "products" => $products,
                "total_item" => $products->count(),
            ],
            "is_error" => false,
            "message" => "Success",
        ], 200);
        // } 
        // else {
        //     // No favorite products
        //     return response()->json([
        //         "data" => [
        //             "products" => $products,
        //         ],
        //         "is_error" => false,
        //         "message" => "Success",
        //     ], 200);
        // }
    }

    // Adding a Product to Favorite List
    public function store($id, Request $request)
    {
        // Check whether product with given `product_id` already exist in favorite table
        $alreadyExist = FavoriteProduct::where('product_id', $request->product_id)->where('user_id', $id)->get()->toArray();
        if (!empty($alreadyExist)) {
            return response()->json([
                "message" => "Product sudah ada di favorite list",
                "data" => null,
                "is_error" => true,
            ], 400);
        }

        $newData = FavoriteProduct::create([
            "user_id" => $id,
            "product_id" => $request->product_id,
        ]);

        if ($newData) {
            $product = Product::where('id', $newData->product_id)->first();
            return response()->json([
                "message" => "success",
                "data" => [
                    "item" => $newData,
                    "product" => $product,
                ],
                "is_error" => false,
            ], 200);
        }

        return response()->json([
            "message" => "Gagal menambahkan product ke favorite list",
            "data" => null,
            "is_error" => true,
            "reason" => "There's something error with your request",
        ], 400);
    }

    // Delete product which matches productId and userId in favorite list
    public function destroy($id, $productId)
    {
        $fav = FavoriteProduct::where('product_id', $productId)->where('user_id', $id)->first();

        if ($fav) {
            $fav->delete();
            return response()->json([
                "message" => "Successfully deleted",
                "is_error" => false
            ], 200);
        }

        return response()->json([
            "message" => "Gagal menghapus, periksa kembali data yang dikirimkan",
            "is_error" => true,
        ], 404);
    }
}
