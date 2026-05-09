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
        Schema::create('product_filter_tags', function (Blueprint $table) {

            $table->id();

            // Group/type
            // Example:
            // pet_type
            // life_stage
            // breed_size
            // color
            $table->string('type');

            // Display name
            // Example:
            // Dog
            // Adult
            // Large
            $table->string('name');

            // URL-safe value
            // Example:
            // dog
            // adult
            // large
            $table->string('slug')->unique();

            // Optional admin control
            $table->boolean('is_active')->default(true);

            // Optional sorting in frontend
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_filter_tags');
    }
};
