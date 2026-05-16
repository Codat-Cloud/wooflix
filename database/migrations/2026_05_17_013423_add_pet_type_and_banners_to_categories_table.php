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
        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('pet_type_tag_id')
                ->nullable()
                ->after('parent_id')
                ->constrained('product_filter_tags')
                ->nullOnDelete();

            $table->string('desktop_banner')->nullable()->after('image');

            $table->string('mobile_banner')->nullable()->after('desktop_banner');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {

            $table->dropForeign(['pet_type_tag_id']);

            $table->dropColumn([
                'pet_type_tag_id',
                'desktop_banner',
                'mobile_banner',
            ]);
        });
    }
};
