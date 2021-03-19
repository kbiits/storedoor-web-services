<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

use function PHPSTORM_META\type;

class UsersCartController extends Controller
{
    // Show all products in cart
    public function index($id, Request $request)
    {
        // $userId = $request->user_id;
        $productsId = Cart::select('product_id')->where('user_id', $id)->get()->toArray();

        $products = Product::whereIn('id', $productsId)->get();
        if (!is_null($products)) {
            // if ($products->count() == 0) {
            //     return response()->json([
            //         "data" => [
            //             "products" => $products,
            //         ],
            //         "is_error" => false,
            //         "message" => "Success",
            //     ], 200);
            // }
            $collectionOfProducts = array();
            foreach ($products as $product) {
                $count = Cart::where('user_id', $id)->where('product_id', $product->id)->count();
                $array_cart = array("product" => $product, "count" => $count);
                array_push($collectionOfProducts, $array_cart);
            }
            // dd($collectionOfProducts);
            return response()->json([
                "data" => [
                    "products" => $collectionOfProducts,
                ],
                "is_error" => false,
                "message" => "Success",
            ], 200);
        } else {
            return response()->json([
                "data" => null,
                "is_error" => true,
                "message" => "Data tidak ketemu",
            ], 404);
        }
    }

    // Adding a Product to Cart
    public function store($id, Request $request)
    {
        $newItemInCart = Cart::create([
            "user_id" => $id,
            "product_id" => $request->product_id,
        ]);

        if ($newItemInCart) {
            return response()->json([
                "message" => "success",
                "data" => [
                    "item" => $newItemInCart,
                ],
                "is_error" => false,
            ], 200);
        }

        return response()->json([
            "message" => "failed",
            "data" => [
                "item" => null,
            ],
            "is_error" => false,
        ], 422);
    }

    // Delete product which matches productId and userId in cart,
    // Important !!! this method just delete 1 item in cart, if in the cart there's more than 1 item with same product, this method just delete 1 of the items with the same product 
    public function destroy($id, $productId)
    {
        $cart = Cart::where('product_id', $productId)->where('user_id', $id)->first();

        if ($cart) {
            $isDeleted = $cart->delete();
            if ($isDeleted) {
                return response()->json([
                    "message" => "Berhasil dihapus",
                    "is_error" => false,
                ], 200);
            }
            return response()->json([
                "message" => "Gagal menghapus, silahkan coba kembali",
                "is_error" => true,
            ], 400);
        }

        return response()->json([
            "message" => "Gagal menghapus, periksa kembali data yang dikirimkan",
            "is_error" => true,
        ], 404);
    }

    // Delete product which matches productId and userId in cart,
    // Important !!! this method delete 1 product in cart, if in the cart there's more than 1 item with same product, this method delete all of the items with the same product 
    public function deleteProduct($id, $productId)
    {
        $cart = Cart::where('product_id', $productId)->where('user_id', $id);

        if ($cart) {
            $isDeleted = $cart->delete();
            if ($isDeleted) {
                return response()->json([
                    "message" => "Berhasil dihapus",
                    "is_error" => false,
                ], 200);
            }
            return response()->json([
                "message" => "Gagal menghapus, silahkan coba kembali",
                "is_error" => true,
            ], 400);
        }

        return response()->json([
            "message" => "Gagal menghapus, periksa kembali data yang dikirimkan",
            "is_error" => true,
        ], 404);
    }
}
