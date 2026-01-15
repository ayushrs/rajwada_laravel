<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddCategoryMenuItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if menu item already exists
        $existingMenu = DB::table('admin_sidebar')
            ->where('name', 'Products')
            ->whereNull('deleted_at')
            ->first();

        if (!$existingMenu) {
            // Insert main menu item for Products
            $mainMenuId = DB::table('admin_sidebar')->insertGetId([
                'name' => 'Products',
                'url' => '#',
                'icon' => 'fas fa-shopping-bag',
                'seq' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert submenu items
            DB::table('admin_sidebar2')->insert([
                [
                    'main_id' => $mainMenuId,
                    'name' => 'View Categories',
                    'url' => 'view_categories',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'main_id' => $mainMenuId,
                    'name' => 'Add Category',
                    'url' => 'add_category_view',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'main_id' => $mainMenuId,
                    'name' => 'View Subcategories',
                    'url' => 'view_subcategories',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'main_id' => $mainMenuId,
                    'name' => 'Add Subcategory',
                    'url' => 'add_subcategory_view',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'main_id' => $mainMenuId,
                    'name' => 'View Products',
                    'url' => 'view_products',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'main_id' => $mainMenuId,
                    'name' => 'Add Product',
                    'url' => 'add_product_view',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }
}
