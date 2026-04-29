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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('slug')->unique();
            $table->longText('content');
            
            // Media
            $table->text('featured_image'); // Original JPG/PNG
            $table->text('image_alt')->nullable();
            
            // SEO
            $table->text('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            
            // Settings
            $table->boolean('is_published')->default(true);
            $table->json('related_posts')->nullable(); // Store IDs of related articles
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
