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

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->text('order_number')->unique();

            $table->decimal('total_amount', 10, 2);

            $table->decimal('shipping_amount', 10, 2)->default(0)->after('total_amount');

            $table->text('status')->default('pending');
            $table->text('payment_status')->default('pending');
            $table->text('payment_method')->nullable();

            $table->text('tracking_number')->nullable();
            $table->text('tracking_url')->nullable();

            $table->decimal('discount', 10, 2)->default(0);

            $table->text('shipping_name')->after('user_id');
            $table->text('shipping_phone')->after('shipping_name');
            $table->text('shipping_address_line1')->after('shipping_phone');
            $table->text('shipping_city')->after('shipping_address_line1');
            $table->text('shipping_state')->after('shipping_city');
            $table->text('shipping_postal_code')->after('shipping_state');
            $table->text('shipping_country')->default('India');

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
