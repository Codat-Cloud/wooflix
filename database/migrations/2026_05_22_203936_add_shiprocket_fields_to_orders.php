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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shiprocket_order_id')
                ->nullable();

            $table->string('shipment_id')
                ->nullable();

            $table->string('awb_code')
                ->nullable();

            $table->string('courier_name')
                ->nullable();

            $table->text('label_url')
                ->nullable();

            $table->text('manifest_url')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
