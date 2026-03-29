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
        Schema::create('home_section_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('home_section_id')
                ->constrained()
                ->cascadeOnDelete();

            // Dynamic reference (brand/category)
            $table->unsignedBigInteger('item_id')->nullable();

            // Custom override (VERY IMPORTANT)
            $table->string('title')->nullable();
            $table->string('image')->nullable();
            $table->string('link')->nullable();

            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_section_items');
    }
};
