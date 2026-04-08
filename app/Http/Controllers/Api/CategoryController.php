<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // ================= ALL CATEGORIES =================
    public function index(Request $request)
    {
        $query = Category::where('is_active', 1);

        // Optional: filter by search term
        if ($request->search) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $categories = $query->orderBy('sort_order')->orderBy('name')->get();

        return response()->json([
            'status' => true,
            'message' => 'Categories fetched successfully',
            'data' => $categories
        ]);
    }

    // ================= SINGLE CATEGORY =================
    public function show($id)
    {
        $category = Category::where('id', $id)
                           ->where('is_active', 1)
                           ->first();

        if (!$category) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Category fetched successfully',
            'data' => $category
        ]);
    }
}