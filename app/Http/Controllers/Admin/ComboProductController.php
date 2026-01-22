<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\adminmodel\ComboProductModal;
use App\adminmodel\ProductModal;
use Illuminate\Support\Str;

class ComboProductController extends Controller
{
    /**
     * Display list of combo products
     */
    public function view_combo_products(Request $req)
    {
        $combos = ComboProductModal::with(['products'])
            ->whereNull('deleted_at')
            ->orderBy('sort_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.combo_product.view_combo_products', ['combos' => $combos]);
    }

    /**
     * Show form to add new combo product
     */
    public function add_combo_product_view(Request $req)
    {
        $products = ProductModal::whereNull('deleted_at')
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'sku', 'price', 'selling_price']);
        return view('admin.combo_product.add_combo_product', ['products' => $products]);
    }

    /**
     * Process adding new combo product
     */
    public function add_combo_product_process(Request $req)
    {
        $admin_id = $req->session()->get('admin_id');

        $req->validate([
            'name' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
        ]);

        $productIds = $req->input('product_id', []);
        $quantities = $req->input('quantity', []);
        $items = [];
        if (is_array($productIds)) {
            foreach ($productIds as $i => $pid) {
                if (empty($pid)) continue;
                $qty = (int) ($quantities[$i] ?? 0);
                if ($qty <= 0) continue;
                $items[$pid] = ($items[$pid] ?? 0) + $qty;
            }
        }
        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', 'Please add at least one product to the combo.');
        }

        $fullimagepath = '';
        if (!empty($req->image)) {
            $allowedFormats = ['jpeg', 'jpg', 'webp'];
            $extension = strtolower($req->image->getClientOriginalExtension());
            if (in_array($extension, $allowedFormats)) {
                $dir = public_path('uploads/image/ComboProducts');
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $file = time() . '_' . Str::random(10) . '.' . $req->image->extension();
                $req->image->move($dir, $file);
                $fullimagepath = 'uploads/image/ComboProducts/' . $file;
            } else {
                return redirect()->back()->with('error', 'Invalid file format. Only jpeg, jpg, and webp files are allowed.');
            }
        }

        $comboInfo = [
            'name' => ucwords($req->input('name')),
            'slug' => Str::slug($req->input('name')),
            'description' => $req->input('description'),
            'price' => $req->input('price'),
            'mrp' => $req->input('mrp'),
            'image' => $fullimagepath,
            'sort_order' => $req->input('sort_order') ?? 0,
            'ip' => $req->ip(),
            'added_by' => $admin_id,
            'is_active' => $req->input('is_active') ?? 1,
        ];

        $combo = ComboProductModal::create($comboInfo);

        foreach ($items as $pid => $qty) {
            $combo->products()->attach($pid, ['quantity' => $qty]);
        }

        return redirect()->route('view_combo_products')->with('success', 'Combo Product Added Successfully.');
    }

    /**
     * Show form to edit combo product
     */
    public function edit_combo_product_view($id, Request $req)
    {
        $combo = ComboProductModal::whereNull('deleted_at')->where('id', base64_decode($id))->first();
        if (empty($combo)) {
            return redirect()->route('view_combo_products')->with('error', 'Combo product not found.');
        }
        $combo->load('products');
        $products = ProductModal::whereNull('deleted_at')
            ->where('is_active', 1)
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'sku', 'price', 'selling_price']);
        return view('admin.combo_product.edit_combo_product', [
            'combo' => $combo,
            'products' => $products,
        ]);
    }

    /**
     * Process updating combo product
     */
    public function update_combo_product_process($id, Request $req)
    {
        $comboId = base64_decode($id);
        $combo = ComboProductModal::whereNull('deleted_at')->where('id', $comboId)->first();

        if (empty($combo)) {
            return redirect()->route('view_combo_products')->with('error', 'Combo product not found.');
        }

        $req->validate([
            'name' => 'required|string|max:500',
            'price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,jpg,webp|max:2048',
        ]);

        $productIds = $req->input('product_id', []);
        $quantities = $req->input('quantity', []);
        $items = [];
        if (is_array($productIds)) {
            foreach ($productIds as $i => $pid) {
                if (empty($pid)) continue;
                $qty = (int) ($quantities[$i] ?? 0);
                if ($qty <= 0) continue;
                $items[$pid] = ($items[$pid] ?? 0) + $qty;
            }
        }
        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', 'Please add at least one product to the combo.');
        }

        $fullimagepath = $combo->image;
        if (!empty($req->image)) {
            $allowedFormats = ['jpeg', 'jpg', 'webp'];
            $extension = strtolower($req->image->getClientOriginalExtension());
            if (in_array($extension, $allowedFormats)) {
                if (!empty($combo->image) && file_exists(public_path($combo->image))) {
                    unlink(public_path($combo->image));
                }
                $dir = public_path('uploads/image/ComboProducts');
                if (!is_dir($dir)) {
                    mkdir($dir, 0755, true);
                }
                $file = time() . '_' . Str::random(10) . '.' . $req->image->extension();
                $req->image->move($dir, $file);
                $fullimagepath = 'uploads/image/ComboProducts/' . $file;
            } else {
                return redirect()->back()->with('error', 'Invalid file format. Only jpeg, jpg, and webp files are allowed.');
            }
        }

        $comboInfo = [
            'name' => ucwords($req->input('name')),
            'slug' => Str::slug($req->input('name')),
            'description' => $req->input('description'),
            'price' => $req->input('price'),
            'mrp' => $req->input('mrp'),
            'image' => $fullimagepath,
            'sort_order' => $req->input('sort_order') ?? 0,
            'is_active' => $req->input('is_active') ?? 1,
        ];

        $combo->update($comboInfo);

        $combo->products()->detach();
        foreach ($items as $pid => $qty) {
            $combo->products()->attach($pid, ['quantity' => $qty]);
        }

        return redirect()->route('view_combo_products')->with('success', 'Combo Product Updated Successfully.');
    }

    /**
     * Update combo product status
     */
    public function update_combo_product_status($status, $id, Request $req)
    {
        $comboId = base64_decode($id);
        $combo = ComboProductModal::whereNull('deleted_at')->where('id', $comboId)->first();

        if (empty($combo)) {
            return redirect()->route('view_combo_products')->with('error', 'Combo product not found.');
        }

        $combo->update(['is_active' => $status == 'active' ? 1 : 0]);
        return redirect()->route('view_combo_products')->with('success', 'Status Updated Successfully.');
    }

    /**
     * Delete combo product
     */
    public function delete_combo_product($id, Request $req)
    {
        $comboId = base64_decode($id);
        $combo = ComboProductModal::whereNull('deleted_at')->where('id', $comboId)->first();

        if (empty($combo)) {
            return redirect()->route('view_combo_products')->with('error', 'Combo product not found.');
        }

        if (!empty($combo->image) && file_exists(public_path($combo->image))) {
            unlink(public_path($combo->image));
        }

        $combo->products()->detach();
        $combo->delete();
        return redirect()->route('view_combo_products')->with('success', 'Combo Product Deleted Successfully.');
    }
}
