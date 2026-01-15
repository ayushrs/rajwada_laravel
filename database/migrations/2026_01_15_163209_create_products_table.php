<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subcategory_id');
            $table->string('name', 500);
            $table->string('sku', 200)->unique()->nullable();
            $table->string('slug', 500)->nullable();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->decimal('mrp', 10, 2)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->decimal('gst_percentage', 5, 2)->default(0);
            $table->decimal('gst', 10, 2)->default(0);
            $table->string('image', 1000)->nullable();
            $table->string('image2', 1000)->nullable();
            $table->string('image3', 1000)->nullable();
            $table->string('image4', 1000)->nullable();
            $table->integer('stock_quantity')->default(0);
            $table->string('size', 100)->nullable();
            $table->string('color', 100)->nullable();
            $table->string('material', 200)->nullable();
            $table->string('brand', 200)->nullable();
            $table->integer('is_top')->default(0);
            $table->integer('is_featured')->default(0);
            $table->integer('is_new_arrival')->default(0);
            $table->string('meta_title', 500)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 1000)->nullable();
            $table->string('ip', 100)->nullable();
            $table->integer('added_by')->nullable();
            $table->integer('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
