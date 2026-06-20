<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop the dependent view first so Postgres releases its lock
        DB::statement("DROP VIEW IF EXISTS abandoned_carts_view");

        // 2. Safely modify the column property
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });

        // 3. Recreate the view with the identical blueprint structure
        DB::statement("
            CREATE VIEW abandoned_carts_view AS
            SELECT 
                MIN(id) as id,
                user_id,
                session_id,
                MAX(updated_at) as last_activity,
                SUM(quantity) as total_qty,
                SUM(price * quantity) as total_value
            FROM cart_items
            GROUP BY user_id, session_id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS abandoned_carts_view");

        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });

        DB::statement("
            CREATE VIEW abandoned_carts_view AS
            SELECT 
                MIN(id) as id,
                user_id,
                session_id,
                MAX(updated_at) as last_activity,
                SUM(quantity) as total_qty,
                SUM(price * quantity) as total_value
            FROM cart_items
            GROUP BY user_id, session_id
        ");
    }
};