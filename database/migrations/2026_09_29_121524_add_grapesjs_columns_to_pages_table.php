<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            // Distinguish standard simple pages from visual builder pages
            $table->string('editor_type')->default('simple')->after('slug'); // 'simple' or 'grapesjs'

            // GrapesJS compiled styles & canvas state
            $table->longText('css')->nullable()->after('content');
            $table->json('gjs_data')->nullable()->after('css');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['editor_type', 'css', 'gjs_data']);
        });
    }
};
