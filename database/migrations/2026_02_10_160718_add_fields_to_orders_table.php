<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->after('id')->unique();
            $table->string('payment_method')->nullable()->after('status');
            $table->string('payment_status')->default('unpaid')->after('payment_method');
            $table->text('address')->nullable()->after('payment_status');
            $table->softDeletes();      
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'order_number',
                'payment_method',
                'payment_status',
                'address'
            ]);
            $table->dropSoftDeletes();
        });
    }
};
