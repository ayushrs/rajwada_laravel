<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\adminmodel\ProductModal;
use App\adminmodel\ComboProductModal;
use App\adminmodel\CategoryModal;
use App\adminmodel\CollectionModal;
use App\adminmodel\ReviewModal;
use Illuminate\Http\Request;

class HomeDataController extends Controller
{
    /**
     * Home page data: featured products, combo products, categories,
     * collections, reviews, and counts.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function homedata(Request $request)
    {
        $limit = $request->input('limit', 10);
        $reviewLimit = $request->input('review_limit', 10);

        // 1. Featured products
        $featureProducts = ProductModal::where('is_active', 1)
            ->where('is_featured', 1)
            ->with(['category', 'subcategory'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        // 2. Combo products
        $comboProducts = ComboProductModal::where('is_active', 1)
            ->with(['products' => function ($q) {
                $q->where('is_active', 1)->select('products.id', 'name', 'slug', 'price', 'selling_price', 'image');
            }])
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        // 3. Categories (with subcategories)
        $categories = CategoryModal::where('is_active', 1)
            ->with(['subcategories' => function ($q) {
                $q->where('is_active', 1)->orderBy('sort_order')->select('id', 'category_id', 'name', 'slug', 'image');
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'description', 'image', 'sort_order']);

        // 4. Collections (with products)
        $collections = CollectionModal::where('is_active', 1)
            ->with(['products' => function ($q) {
                $q->where('is_active', 1)->select('products.id', 'name', 'slug', 'price', 'selling_price', 'image');
            }])
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'slug', 'description', 'image', 'sort_order']);

        // 5. Reviews (approved, latest)
        $reviews = ReviewModal::where('is_approved', 1)
            ->with(['product' => function ($q) {
                $q->select('id', 'name', 'slug', 'image');
            }])
            ->orderBy('created_at', 'desc')
            ->limit($reviewLimit)
            ->get();

        // 6. Count
        $count = [
            'feature_products' => ProductModal::where('is_active', 1)->where('is_featured', 1)->count(),
            'combo_products'   => ComboProductModal::where('is_active', 1)->count(),
            'categories'       => CategoryModal::where('is_active', 1)->count(),
            'collections'      => CollectionModal::where('is_active', 1)->count(),
            'reviews'          => ReviewModal::where('is_approved', 1)->count(),
            'products'         => ProductModal::where('is_active', 1)->count(),
        ];

        return response()->json([
            'status'  => true,
            'message' => 'Home data fetched successfully',
            'data'    => [
                'feature_products' => $featureProducts,
                'combo_products'   => $comboProducts,
                'categories'       => $categories,
                'collections'      => $collections,
                'reviews'          => $reviews,
                'count'            => $count,
            ],
        ]);
    }
}
