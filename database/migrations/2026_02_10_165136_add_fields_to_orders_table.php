<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            if (!Schema::hasColumn('orders', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('orders', 'address_id')) {
                $table->unsignedBigInteger('address_id')->nullable()->after('user_id');
            }

            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('orders', 'tax')) {
                $table->decimal('tax', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('orders', 'shipping')) {
                $table->decimal('shipping', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 10, 2)->nullable();
            }

            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }

            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status')->nullable();
            }

            if (!Schema::hasColumn('orders', 'order_status')) {
                $table->string('order_status')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Optional: usually left empty for safe migrations
    }
};
