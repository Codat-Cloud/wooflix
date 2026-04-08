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
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // ownership (future vendor support)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();

            $table->text('name');
            $table->text('slug')->unique();
            $table->text('main_image');
            

            $table->longText('short_description')->nullable();
            $table->longText('description')->nullable();

            // product flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            // base price (fallback if no variants)
            $table->decimal('base_price', 10, 2)->nullable();
            $table->decimal('sale_price', 10, 2)->nullable();

            // SEO
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Marketing
            $table->text('custom_tracking_script')->nullable();

            $table->timestamps();

            $table->index(['category_id']);
            $table->index(['brand_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
