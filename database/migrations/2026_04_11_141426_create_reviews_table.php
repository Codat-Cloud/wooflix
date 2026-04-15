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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            // Relationships
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // Content
            $table->string('customer_name'); // For guests or custom display names
            $table->string('customer_email');
            $table->integer('rating')->default(5); // 1 to 5 stars
            $table->text('comment');
            
            // Status & Verification
            $table->boolean('is_approved')->default(false); // Moderation
            $table->boolean('is_verified_buyer')->default(false); // If they actually bought it

            $table->integer('likes')->default(0);
            $table->integer('dislikes')->default(0);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
