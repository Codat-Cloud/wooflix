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
        Schema::create('home_sections', function (Blueprint $table) {
            $table->id();

            // Section content
            $table->string('title');
            $table->string('subtitle')->nullable();

            // What this section shows
            $table->enum('type', ['brand', 'category', 'custom']);

            // Layout type (VERY IMPORTANT)
            $table->enum('layout', [
                'scroll',     // horizontal scroll (brands)
                'grid_4',     // 4 items
                'grid_6',     // 6 items
                'grid_8'      // 8 items
            ])->default('scroll');

            // Ordering + visibility
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
