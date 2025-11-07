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
        $tables = [
            'prospects',
            'clients',
            'follow_ups',
            'blog_posts',
            'blog_categories',
            'blog_tags',
            'contact_messages'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
                $blueprint->index('company_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'prospects',
            'clients',
            'follow_ups',
            'blog_posts',
            'blog_categories',
            'blog_tags',
            'contact_messages'
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $blueprint) {
                $blueprint->dropForeign(['company_id']);
                $blueprint->dropIndex([' company_id']);
                $blueprint->dropColumn('company_id');
            });
        }
    }
};
