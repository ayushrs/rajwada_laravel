<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // ================= ADD TO CART =================
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $userId = $request->user()->id;

        $cart = Cart::where('user_id', $userId)
                    ->where('product_id', $request->product_id)
                    ->first();

        if ($cart) {
            $cart->quantity += $request->quantity ?? 1;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity ?? 1
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart'
        ]);
    }

    // ================= GET CART =================
    public function getCart(Request $request)
    {
        $cartItems = Cart::with('product')
                        ->where('user_id', $request->user()->id)
                        ->get();

        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item->product->price * $item->quantity;
        }

        return response()->json([
            'status' => true,
            'cart_items' => $cartItems,
            'total_amount' => $total
        ]);
    }

    // ================= REMOVE FROM CART =================
    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:carts,product_id'
        ]);

        Cart::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product removed from cart'
        ]);
    }
}
