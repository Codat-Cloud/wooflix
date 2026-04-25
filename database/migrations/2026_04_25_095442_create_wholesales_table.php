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
        Schema::create('wholesales', function (Blueprint $table) {
            $table->id();

            // ================= CONTACT =================
            $table->text('full_name');
            $table->text('business_name');
            $table->text('email');
            $table->text('phone');

            // ================= BUSINESS =================
            $table->text('business_type');
            $table->text('gst_number')->nullable();

            $table->text('address');
            $table->text('city');
            $table->text('state');
            $table->text('postal_code');

            // ================= INTEREST =================
            $table->json('products_interested')->nullable(); // multi-select
            $table->text('monthly_quantity')->nullable();

            $table->boolean('sells_pet_products')->default(false);
            $table->text('brands')->nullable();

            $table->json('sales_channels')->nullable(); // multi-select

            // ================= OTHER =================
            $table->text('message')->nullable();
            $table->boolean('consent')->default(true);

            // ================= SYSTEM =================
            $table->text('status')->default('new'); // new, contacted, converted
            $table->text('source')->default('website');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wholesales');
    }
};
