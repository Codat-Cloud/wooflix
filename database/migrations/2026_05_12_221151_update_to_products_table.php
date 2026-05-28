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
        Schema::table('products', function (Blueprint $table) {

            $table->jsonb('filters')->nullable();
            $table->text('asin')->nullable();
            $table->text('hsn')->nullable();
            $table->dropColumn([
                'base_price',
                'sale_price'
            ]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'filters',
                'hsn',
                'asin'
            ]);
        });
    }
};
