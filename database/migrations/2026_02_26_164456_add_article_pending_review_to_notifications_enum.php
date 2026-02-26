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
        DB::statement("ALTER TABLE notifications MODIFY type ENUM('new_comment','new_like','new_debate','article_approved','system','new_article','article_pending_review') NOT NULL DEFAULT 'system'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE notifications MODIFY type ENUM('new_comment','new_like','new_debate','article_approved','system','new_article') NOT NULL DEFAULT 'system'");
    }
};
