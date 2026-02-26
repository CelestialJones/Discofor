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
        // append "new_article" to existing enum by modifying column directly
        DB::statement("ALTER TABLE notifications MODIFY type ENUM('new_comment','new_like','new_debate','article_approved','system','new_article') NOT NULL DEFAULT 'system';");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ideally remove 'new_article' value (not easily reversible in SQL); keep as is
    }
};
