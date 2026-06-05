<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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
    }
};
