<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('store_id');
            $table->foreignId('address_id');
            $table->foreignId('driver_id')->nullable();
            $table->enum('status', ['pending', 'processing', 'out_for_delivery', 'completed', 'cancelled'])->default('pending');
            $table->string('payment_id')->nullable();
            $table->enum('payment_method', ['card', 'cod'])->default('card');
            $table->enum('payment_status', ['pending', 'completed', 'failed'])->default('pending');
            $table->enum('shipping_method', ['delivery', 'takeaway'])->default('delivery');
            $table->enum('shipping_status', ['pending', 'completed', 'failed'])->default('pending');
            $table->integer('delivery_time')->default(0);
    
            $table->decimal('products_price', 10, 2)->default(0);
            $table->decimal('shipping_price', 10, 2)->default(0);

            $table->string('coupon_code')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            
            $table->decimal('tip', 10, 2)->default(0);

            $table->decimal('total_price', 10, 2)->default(0);

            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
