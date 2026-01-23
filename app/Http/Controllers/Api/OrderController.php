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
    // ================= PLACE ORDER =================
    public function placeOrder(Request $request)
    {
        $user = $request->user();

        $cartItems = Cart::with('product')
                        ->where('user_id', $user->id)
                        ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'Cart is empty'
            ], 400);
        }

        DB::beginTransaction();

        try {
            $total = 0;

            foreach ($cartItems as $item) {
                $total += $item->product->price * $item->quantity;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'pending'
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);
            }

            Cart::where('user_id', $user->id)->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Order placed successfully',
                'order_id' => $order->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Order failed',
                'error' => $e->getMessage()
            ], 500);
        }
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
