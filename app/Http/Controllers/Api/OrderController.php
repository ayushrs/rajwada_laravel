<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\OrderItem as ModelsOrderItem;
use App\Models\OrderItem as AppModelsOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

public function calculate(Request $request)
{
    $cartItems = Cart::with('product')
        ->where('user_id', $request->user()->id)
        ->get();

    if ($cartItems->isEmpty()) {
        return response()->json(['message'=>'Cart is empty'], 400);
    }

    $subtotal = 0;

    foreach ($cartItems as $item) {
        $subtotal += $item->product->price * $item->quantity;
    }

    $tax = $subtotal * 0.05; // 5%
    $shipping = 50;
    $total = $subtotal + $tax + $shipping;

    return response()->json([
        'subtotal' => $subtotal,
        'tax' => $tax,
        'shipping' => $shipping,
        'total' => $total
    ]);
}

    // ================= PLACE ORDER =================
  public function placeOrder(Request $request)  
{
    $request->validate([
        'address_id' => 'required|exists:addresses,id',
        'payment_method' => 'required|in:cod,online'
    ]);

    $cartItems = Cart::with('product')
        ->where('user_id', $request->user()->id)
        ->get();

    if ($cartItems->isEmpty()) {
        return response()->json(['message'=>'Cart empty'], 400);
    }

    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += $item->product->price * $item->quantity;
    }

    $tax = $subtotal * 0.05;
    $shipping = 50;
    $total = $subtotal + $tax + $shipping;

    $order = Order::create([
        'user_id' => $request->user()->id,
        'address_id' => $request->address_id,
        'subtotal' => $subtotal,
        'tax' => $tax,
        'shipping' => $shipping,
        'total' => $total,
        'payment_method' => $request->payment_method,
        'payment_status' => $request->payment_method == 'cod' ? 'pending' : 'paid',
    ]);

    foreach ($cartItems as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price
        ]);
    }

    Cart::where('user_id', $request->user()->id)->delete();

    return response()->json([
        'status'=>true,
        'message'=>'Order placed successfully',
        'order_id'=>$order->id
    ]);
}


    // ================= USER ORDERS =================
    public function myOrders(Request $request)
    {
        $orders = Order::with('items')
                        ->where('user_id', $request->user()->id)
                        ->latest()
                        ->get();

        return response()->json([
            'status' => true,
            'orders' => $orders
        ]);
    }

    // ================= SINGLE ORDER =================
    public function orderDetail($id, Request $request)
    {
        $order = Order::with('items')
                      ->where('id', $id)
                      ->where('user_id', $request->user()->id)
                      ->first();

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'order' => $order
        ]);
    }
}
