<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();   // CBI-2024-0001
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax', 15, 2)->default(0);
            $table->decimal('total', 15, 2);
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_province');
            $table->string('shipping_postal_code');
            $table->string('shipping_phone');
            $table->text('notes')->nullable();
            $table->enum('status', [
                'pending',      // menunggu konfirmasi
                'confirmed',    // dikonfirmasi admin
                'processing',   // sedang diproses
                'shipped',      // sudah dikirim
                'delivered',    // sudah diterima
                'cancelled'     // dibatalkan
            ])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products');
            $table->string('product_name');     // snapshot nama saat order
            $table->string('product_sku');      // snapshot SKU saat order
            $table->integer('quantity');
            $table->decimal('price', 15, 2);    // harga saat order
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};