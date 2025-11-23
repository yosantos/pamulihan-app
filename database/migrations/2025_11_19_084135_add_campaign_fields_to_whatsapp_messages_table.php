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
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->foreignId('campaign_id')->nullable()->after('message')->constrained('whatsapp_campaigns')->onDelete('set null');
            $table->json('variables_used')->nullable()->after('campaign_id');

            // Index for campaign queries
            $table->index('campaign_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropForeign(['campaign_id']);
            $table->dropIndex(['campaign_id']);
            $table->dropColumn(['campaign_id', 'variables_used']);
        });
    }
};
