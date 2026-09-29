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
        Schema::create('footer_link_groups', function (Blueprint $table) {
            $table->id();

            // NULL = Main Group Header (e.g., "SHOP FOR", "QUICK LINKS")
            // NOT NULL = Child Link (e.g., "Dogs", "Contact Us")
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('footer_link_groups')
                ->cascadeOnDelete();

            $table->string('name');          // Group Name or Link Name
            $table->string('url')->nullable(); // Destination path (only needed for links)
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
        Schema::dropIfExists('footer_link_groups');
    }
};
