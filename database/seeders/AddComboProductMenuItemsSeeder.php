<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddComboProductMenuItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productsMenu = DB::table('admin_sidebar')
            ->where('name', 'Products')
            ->whereNull('deleted_at')
            ->first();

        if (!$productsMenu) {
            return;
        }

        $existing = DB::table('admin_sidebar2')
            ->where('main_id', $productsMenu->id)
            ->where('url', 'view_combo_products')
            ->whereNull('deleted_at')
            ->first();

        if ($existing) {
            return;
        }

        DB::table('admin_sidebar2')->insert([
            [
                'main_id' => $productsMenu->id,
                'name' => 'View Combo Products',
                'url' => 'view_combo_products',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'main_id' => $productsMenu->id,
                'name' => 'Add Combo Product',
                'url' => 'add_combo_product_view',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
