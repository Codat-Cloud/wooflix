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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->string('code')->unique()->index();
            $table->string('type'); // fixed, percentage, free_shipping
            $table->decimal('value', 10, 2)->default(0);
            $table->decimal('min_spend', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable(); // Crucial for % coupons
            
            $table->integer('usage_limit')->nullable(); // Total times coupon can be used
            $table->integer('user_limit')->default(1);   // Times a single user can use it
            
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            
            $table->boolean('is_best')->default(false);
            $table->boolean('is_visible')->default(true); // Show in the "Available Offers" UI?
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
