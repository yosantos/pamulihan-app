<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the status enum to include 'pending'
        DB::statement("ALTER TABLE whatsapp_messages MODIFY COLUMN status ENUM('pending', 'sent', 'failed') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE whatsapp_messages MODIFY COLUMN status ENUM('sent', 'failed') NOT NULL DEFAULT 'sent'");
    }
};
