<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\adminmodel\CategoryModal;
use App\adminmodel\SubcategoryModal;
use App\adminmodel\ProductModal;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // ==================== CATEGORIES ====================
    
    /**
     * Display list of categories
     */
    public function view_categories(Request $req)
    {
        $categories = CategoryModal::whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin/category/view_categories', ['categories' => $categories]);
    }

    /**
     * Show form to add new category
     */
    public function add_category_view(Request $req)
    {
        return view('admin/category/add_category');
    }

    /**
     * Process adding new category
     */
    public function add_category_process(Request $req)
    {
        $admin_id = $req->session()->get('admin_id');
        
        $req->validate([
            'name' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
        ]);

        $fullimagepath = '';
        if (!empty($req->image)) {
            $allowedFormats = ['jpeg', 'jpg', 'webp'];
            $extension = strtolower($req->image->getClientOriginalExtension());
            if (in_array($extension, $allowedFormats)) {
                $file = time() . '_' . Str::random(10) . '.' . $req->image->extension();
                $req->image->move(public_path('uploads/image/Categories/'), $file);
                $fullimagepath = 'uploads/image/Categories/' . $file;
            } else {
                return redirect()->back()->with('error', 'Invalid file format. Only jpeg, jpg, and webp files are allowed.');
            }
        }

        $categoryInfo = [
            'name' => ucwords($req->input('name')),
            'slug' => Str::slug($req->input('name')),
            'description' => $req->input('description'),
            'image' => $fullimagepath,
            'sort_order' => $req->input('sort_order') ?? 0,
            'meta_title' => $req->input('meta_title'),
            'meta_description' => $req->input('meta_description'),
            'ip' => $req->ip(),
            'added_by' => $admin_id,
            'is_active' => $req->input('is_active') ?? 1,
        ];

        CategoryModal::create($categoryInfo);
        return redirect()->route('view_categories')->with('success', 'Category Added Successfully.');
    }

    /**
     * Show form to edit category
     */
    public function edit_category_view($id, Request $req)
    {
        $category = CategoryModal::whereNull('deleted_at')->where('id', base64_decode($id))->first();
        if (empty($category)) {
            return redirect()->route('view_categories')->with('error', 'Category not found.');
        }
        return view('admin/category/edit_category', ['category' => $category]);
    }

    /**
     * Process updating category
     */
    public function update_category_process($id, Request $req)
    {
        $categoryId = base64_decode($id);
        $category = CategoryModal::whereNull('deleted_at')->where('id', $categoryId)->first();
        
        if (empty($category)) {
            return redirect()->route('view_categories')->with('error', 'Category not found.');
        }

        $req->validate([
            'name' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
        ]);

        $fullimagepath = $category->image;
        if (!empty($req->image)) {
            $allowedFormats = ['jpeg', 'jpg', 'webp'];
            $extension = strtolower($req->image->getClientOriginalExtension());
            if (in_array($extension, $allowedFormats)) {
                // Delete old image if exists
                if (!empty($category->image) && file_exists(public_path($category->image))) {
                    unlink(public_path($category->image));
                }
                $file = time() . '_' . Str::random(10) . '.' . $req->image->extension();
                $req->image->move(public_path('uploads/image/Categories/'), $file);
                $fullimagepath = 'uploads/image/Categories/' . $file;
            } else {
                return redirect()->back()->with('error', 'Invalid file format. Only jpeg, jpg, and webp files are allowed.');
            }
        }

        $categoryInfo = [
            'name' => ucwords($req->input('name')),
            'slug' => Str::slug($req->input('name')),
            'description' => $req->input('description'),
            'image' => $fullimagepath,
            'sort_order' => $req->input('sort_order') ?? 0,
            'meta_title' => $req->input('meta_title'),
            'meta_description' => $req->input('meta_description'),
            'is_active' => $req->input('is_active') ?? 1,
        ];

        $category->update($categoryInfo);
        return redirect()->route('view_categories')->with('success', 'Category Updated Successfully.');
    }

    /**
     * Update category status
     */
    public function update_category_status($status, $id, Request $req)
    {
        $categoryId = base64_decode($id);
        $category = CategoryModal::whereNull('deleted_at')->where('id', $categoryId)->first();
        
        if (empty($category)) {
            return redirect()->route('view_categories')->with('error', 'Category not found.');
        }

        $category->update(['is_active' => $status == 'active' ? 1 : 0]);
        return redirect()->route('view_categories')->with('success', 'Status Updated Successfully.');
    }

    /**
     * Delete category
     */
    public function delete_category($id, Request $req)
    {
        $categoryId = base64_decode($id);
        $category = CategoryModal::whereNull('deleted_at')->where('id', $categoryId)->first();
        
        if (empty($category)) {
            return redirect()->route('view_categories')->with('error', 'Category not found.');
        }

        // Delete image if exists
        if (!empty($category->image) && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $category->delete();
        return redirect()->route('view_categories')->with('success', 'Category Deleted Successfully.');
    }

    // ==================== SUBCATEGORIES ====================

    /**
     * Display list of subcategories
     */
    public function view_subcategories(Request $req)
    {
        $subcategories = SubcategoryModal::with('category')
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin/category/view_subcategories', ['subcategories' => $subcategories]);
    }

    /**
     * Show form to add new subcategory
     */
    public function add_subcategory_view(Request $req)
    {
        $categories = CategoryModal::whereNull('deleted_at')
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();
        return view('admin/category/add_subcategory', ['categories' => $categories]);
    }

    /**
     * Process adding new subcategory
     */
    public function add_subcategory_process(Request $req)
    {
        $admin_id = $req->session()->get('admin_id');
        
        $req->validate([
            'category_id' => 'required|exists:category,id',
            'name' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
        ]);

        $fullimagepath = '';
        if (!empty($req->image)) {
            $allowedFormats = ['jpeg', 'jpg', 'webp'];
            $extension = strtolower($req->image->getClientOriginalExtension());
            if (in_array($extension, $allowedFormats)) {
                $file = time() . '_' . Str::random(10) . '.' . $req->image->extension();
                $req->image->move(public_path('uploads/image/Subcategories/'), $file);
                $fullimagepath = 'uploads/image/Subcategories/' . $file;
            } else {
                return redirect()->back()->with('error', 'Invalid file format. Only jpeg, jpg, and webp files are allowed.');
            }
        }

        $subcategoryInfo = [
            'category_id' => $req->input('category_id'),
            'name' => ucwords($req->input('name')),
            'slug' => Str::slug($req->input('name')),
            'description' => $req->input('description'),
            'image' => $fullimagepath,
            'sort_order' => $req->input('sort_order') ?? 0,
            'meta_title' => $req->input('meta_title'),
            'meta_description' => $req->input('meta_description'),
            'ip' => $req->ip(),
            'added_by' => $admin_id,
            'is_active' => $req->input('is_active') ?? 1,
        ];

        SubcategoryModal::create($subcategoryInfo);
        return redirect()->route('view_subcategories')->with('success', 'Subcategory Added Successfully.');
    }

    /**
     * Show form to edit subcategory
     */
    public function edit_subcategory_view($id, Request $req)
    {
        $subcategory = SubcategoryModal::whereNull('deleted_at')->where('id', base64_decode($id))->first();
        if (empty($subcategory)) {
            return redirect()->route('view_subcategories')->with('error', 'Subcategory not found.');
        }
        $categories = CategoryModal::whereNull('deleted_at')
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();
        return view('admin/category/edit_subcategory', ['subcategory' => $subcategory, 'categories' => $categories]);
    }

    /**
     * Process updating subcategory
     */
    public function update_subcategory_process($id, Request $req)
    {
        $subcategoryId = base64_decode($id);
        $subcategory = SubcategoryModal::whereNull('deleted_at')->where('id', $subcategoryId)->first();
        
        if (empty($subcategory)) {
            return redirect()->route('view_subcategories')->with('error', 'Subcategory not found.');
        }

        $req->validate([
            'category_id' => 'required|exists:category,id',
            'name' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
        ]);

        $fullimagepath = $subcategory->image;
        if (!empty($req->image)) {
            $allowedFormats = ['jpeg', 'jpg', 'webp'];
            $extension = strtolower($req->image->getClientOriginalExtension());
            if (in_array($extension, $allowedFormats)) {
                if (!empty($subcategory->image) && file_exists(public_path($subcategory->image))) {
                    unlink(public_path($subcategory->image));
                }
                $file = time() . '_' . Str::random(10) . '.' . $req->image->extension();
                $req->image->move(public_path('uploads/image/Subcategories/'), $file);
                $fullimagepath = 'uploads/image/Subcategories/' . $file;
            } else {
                return redirect()->back()->with('error', 'Invalid file format. Only jpeg, jpg, and webp files are allowed.');
            }
        }

        $subcategoryInfo = [
            'category_id' => $req->input('category_id'),
            'name' => ucwords($req->input('name')),
            'slug' => Str::slug($req->input('name')),
            'description' => $req->input('description'),
            'image' => $fullimagepath,
            'sort_order' => $req->input('sort_order') ?? 0,
            'meta_title' => $req->input('meta_title'),
            'meta_description' => $req->input('meta_description'),
            'is_active' => $req->input('is_active') ?? 1,
        ];

        $subcategory->update($subcategoryInfo);
        return redirect()->route('view_subcategories')->with('success', 'Subcategory Updated Successfully.');
    }

    /**
     * Update subcategory status
     */
    public function update_subcategory_status($status, $id, Request $req)
    {
        $subcategoryId = base64_decode($id);
        $subcategory = SubcategoryModal::whereNull('deleted_at')->where('id', $subcategoryId)->first();
        
        if (empty($subcategory)) {
            return redirect()->route('view_subcategories')->with('error', 'Subcategory not found.');
        }

        $subcategory->update(['is_active' => $status == 'active' ? 1 : 0]);
        return redirect()->route('view_subcategories')->with('success', 'Status Updated Successfully.');
    }

    /**
     * Delete subcategory
     */
    public function delete_subcategory($id, Request $req)
    {
        $subcategoryId = base64_decode($id);
        $subcategory = SubcategoryModal::whereNull('deleted_at')->where('id', $subcategoryId)->first();
        
        if (empty($subcategory)) {
            return redirect()->route('view_subcategories')->with('error', 'Subcategory not found.');
        }

        if (!empty($subcategory->image) && file_exists(public_path($subcategory->image))) {
            unlink(public_path($subcategory->image));
        }

        $subcategory->delete();
        return redirect()->route('view_subcategories')->with('success', 'Subcategory Deleted Successfully.');
    }

    // ==================== PRODUCTS ====================

    /**
     * Display list of products
     */
    public function view_products(Request $req)
    {
        $products = ProductModal::with(['category', 'subcategory'])
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin/category/view_products', ['products' => $products]);
    }

    /**
     * Show form to add new product
     */
    public function add_product_view(Request $req)
    {
        $categories = CategoryModal::whereNull('deleted_at')
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();
        return view('admin/category/add_product', ['categories' => $categories]);
    }

    /**
     * Get subcategories by category ID (AJAX)
     */
    public function get_subcategories(Request $req)
    {
        $categoryId = $req->input('category_id');
        $subcategories = SubcategoryModal::whereNull('deleted_at')
            ->where('category_id', $categoryId)
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();
        return response()->json($subcategories);
    }

    /**
     * Process adding new product
     */
    public function add_product_process(Request $req)
    {
        $admin_id = $req->session()->get('admin_id');
        
        $req->validate([
            'category_id' => 'required|exists:category,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'name' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|unique:products,sku',
            'image' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
        ]);

        // Handle image uploads
        $imagePaths = ['', '', '', ''];
        $imageFields = ['image', 'image2', 'image3', 'image4'];
        
        for ($i = 0; $i < count($imageFields); $i++) {
            if (!empty($req->{$imageFields[$i]})) {
                $allowedFormats = ['jpeg', 'jpg', 'webp'];
                $extension = strtolower($req->{$imageFields[$i]}->getClientOriginalExtension());
                if (in_array($extension, $allowedFormats)) {
                    $file = time() . '_' . Str::random(10) . '_' . $i . '.' . $req->{$imageFields[$i]}->extension();
                    $req->{$imageFields[$i]}->move(public_path('uploads/image/Products/'), $file);
                    $imagePaths[$i] = 'uploads/image/Products/' . $file;
                } else {
                    return redirect()->back()->with('error', 'Invalid file format for image ' . ($i + 1) . '. Only jpeg, jpg, and webp files are allowed.');
                }
            }
        }

        // Calculate GST and selling price
        $price = $req->input('price');
        $gstPercentage = $req->input('gst_percentage') ?? 0;
        $gst = ($price * $gstPercentage) / 100;
        $sellingPrice = $req->input('selling_price') ?? ($price + $gst);

        $productInfo = [
            'category_id' => $req->input('category_id'),
            'subcategory_id' => $req->input('subcategory_id'),
            'name' => ucwords($req->input('name')),
            'sku' => $req->input('sku'),
            'slug' => Str::slug($req->input('name')),
            'short_description' => $req->input('short_description'),
            'description' => $req->input('description'),
            'mrp' => $req->input('mrp'),
            'price' => $price,
            'gst_percentage' => $gstPercentage,
            'gst' => $gst,
            'selling_price' => $sellingPrice,
            'image' => $imagePaths[0],
            'image2' => $imagePaths[1],
            'image3' => $imagePaths[2],
            'image4' => $imagePaths[3],
            'stock_quantity' => $req->input('stock_quantity') ?? 0,
            'size' => $req->input('size'),
            'color' => $req->input('color'),
            'material' => $req->input('material'),
            'brand' => $req->input('brand'),
            'is_top' => $req->input('is_top') ?? 0,
            'is_featured' => $req->input('is_featured') ?? 0,
            'is_new_arrival' => $req->input('is_new_arrival') ?? 0,
            'meta_title' => $req->input('meta_title'),
            'meta_description' => $req->input('meta_description'),
            'meta_keywords' => $req->input('meta_keywords'),
            'ip' => $req->ip(),
            'added_by' => $admin_id,
            'is_active' => $req->input('is_active') ?? 1,
        ];

        ProductModal::create($productInfo);
        return redirect()->route('view_products')->with('success', 'Product Added Successfully.');
    }

    /**
     * Show form to edit product
     */
    public function edit_product_view($id, Request $req)
    {
        $product = ProductModal::whereNull('deleted_at')->where('id', base64_decode($id))->first();
        if (empty($product)) {
            return redirect()->route('view_products')->with('error', 'Product not found.');
        }
        $categories = CategoryModal::whereNull('deleted_at')
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();
        $subcategories = SubcategoryModal::whereNull('deleted_at')
            ->where('category_id', $product->category_id)
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get();
        return view('admin/category/edit_product', [
            'product' => $product,
            'categories' => $categories,
            'subcategories' => $subcategories
        ]);
    }

    /**
     * Process updating product
     */
    public function update_product_process($id, Request $req)
    {
        $productId = base64_decode($id);
        $product = ProductModal::whereNull('deleted_at')->where('id', $productId)->first();
        
        if (empty($product)) {
            return redirect()->route('view_products')->with('error', 'Product not found.');
        }

        $req->validate([
            'category_id' => 'required|exists:category,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'name' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|unique:products,sku,' . $productId,
            'image' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
            'image2' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
            'image3' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
            'image4' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
        ]);

        // Handle image uploads
        $imageFields = ['image', 'image2', 'image3', 'image4'];
        $imagePaths = [
            $product->image,
            $product->image2,
            $product->image3,
            $product->image4
        ];
        
        for ($i = 0; $i < count($imageFields); $i++) {
            if (!empty($req->{$imageFields[$i]})) {
                $allowedFormats = ['jpeg', 'jpg', 'webp'];
                $extension = strtolower($req->{$imageFields[$i]}->getClientOriginalExtension());
                if (in_array($extension, $allowedFormats)) {
                    // Delete old image if exists
                    $oldImageField = $i == 0 ? 'image' : 'image' . ($i + 1);
                    if (!empty($product->{$oldImageField}) && file_exists(public_path($product->{$oldImageField}))) {
                        unlink(public_path($product->{$oldImageField}));
                    }
                    $file = time() . '_' . Str::random(10) . '_' . $i . '.' . $req->{$imageFields[$i]}->extension();
                    $req->{$imageFields[$i]}->move(public_path('uploads/image/Products/'), $file);
                    $imagePaths[$i] = 'uploads/image/Products/' . $file;
                } else {
                    return redirect()->back()->with('error', 'Invalid file format for image ' . ($i + 1) . '. Only jpeg, jpg, and webp files are allowed.');
                }
            }
        }

        // Calculate GST and selling price
        $price = $req->input('price');
        $gstPercentage = $req->input('gst_percentage') ?? 0;
        $gst = ($price * $gstPercentage) / 100;
        $sellingPrice = $req->input('selling_price') ?? ($price + $gst);

        $productInfo = [
            'category_id' => $req->input('category_id'),
            'subcategory_id' => $req->input('subcategory_id'),
            'name' => ucwords($req->input('name')),
            'sku' => $req->input('sku'),
            'slug' => Str::slug($req->input('name')),
            'short_description' => $req->input('short_description'),
            'description' => $req->input('description'),
            'mrp' => $req->input('mrp'),
            'price' => $price,
            'gst_percentage' => $gstPercentage,
            'gst' => $gst,
            'selling_price' => $sellingPrice,
            'image' => $imagePaths[0],
            'image2' => $imagePaths[1],
            'image3' => $imagePaths[2],
            'image4' => $imagePaths[3],
            'stock_quantity' => $req->input('stock_quantity') ?? 0,
            'size' => $req->input('size'),
            'color' => $req->input('color'),
            'material' => $req->input('material'),
            'brand' => $req->input('brand'),
            'is_top' => $req->input('is_top') ?? 0,
            'is_featured' => $req->input('is_featured') ?? 0,
            'is_new_arrival' => $req->input('is_new_arrival') ?? 0,
            'meta_title' => $req->input('meta_title'),
            'meta_description' => $req->input('meta_description'),
            'meta_keywords' => $req->input('meta_keywords'),
            'is_active' => $req->input('is_active') ?? 1,
        ];

        $product->update($productInfo);
        return redirect()->route('view_products')->with('success', 'Product Updated Successfully.');
    }

    /**
     * Update product status
     */
    public function update_product_status($status, $id, Request $req)
    {
        $productId = base64_decode($id);
        $product = ProductModal::whereNull('deleted_at')->where('id', $productId)->first();
        
        if (empty($product)) {
            return redirect()->route('view_products')->with('error', 'Product not found.');
        }

        $product->update(['is_active' => $status == 'active' ? 1 : 0]);
        return redirect()->route('view_products')->with('success', 'Status Updated Successfully.');
    }

    /**
     * Delete product
     */
    public function delete_product($id, Request $req)
    {
        $productId = base64_decode($id);
        $product = ProductModal::whereNull('deleted_at')->where('id', $productId)->first();
        
        if (empty($product)) {
            return redirect()->route('view_products')->with('error', 'Product not found.');
        }

        // Delete images if exist
        $imageFields = ['image', 'image2', 'image3', 'image4'];
        foreach ($imageFields as $field) {
            if (!empty($product->{$field}) && file_exists(public_path($product->{$field}))) {
                unlink(public_path($product->{$field}));
            }
        }

        $product->delete();
        return redirect()->route('view_products')->with('success', 'Product Deleted Successfully.');
    }
}
