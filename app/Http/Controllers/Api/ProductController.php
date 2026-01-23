<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\adminmodel\ProductModal;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ================= ALL PRODUCTS =================
    public function index(Request $request)
    {
        $query = ProductModal::where('is_active', 1);

        // Filter by category
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by subcategory
        if ($request->subcategory_id) {
            $query->where('subcategory_id', $request->subcategory_id);
        }

        $products = $query->latest()->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Products fetched successfully',
            'data' => $products
        ]);
    }

    // ================= SINGLE PRODUCT =================
    public function show($id)
    {
        $product = ProductModal::where('id', $id)
                          ->where('is_active', 1)
                          ->first();

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
    }

    public function search(Request $request)
{
    $query = ProductModal::where('is_active', 1);

    // Search by keyword
    if ($request->keyword) {
        $query->where('name', 'LIKE', '%' . $request->keyword . '%');
    }

    // Filter by category
    if ($request->category_id) {
        $query->where('category_id', $request->category_id);
    }

    // Filter by price range
    if ($request->min_price) {
        $query->where('price', '>=', $request->min_price);
    }

    if ($request->max_price) {
        $query->where('price', '<=', $request->max_price);
    }

    $products = $query->latest()->paginate(10);

    return response()->json([
        'status' => true,
        'message' => 'Search results',
        'data' => $products
    ]);
}
}
