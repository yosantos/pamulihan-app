<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('whatsapp_campaigns', function (Blueprint $table) {
            $table->uuid('campaign_code')->nullable()->after('id');
            $table->uuid('api_key')->nullable()->after('campaign_code');
        });

        // Generate UUIDs for existing campaigns
        DB::table('whatsapp_campaigns')->get()->each(function ($campaign) {
            DB::table('whatsapp_campaigns')
                ->where('id', $campaign->id)
                ->update([
                    'campaign_code' => (string) Str::uuid(),
                    'api_key' => (string) Str::uuid(),
                ]);
        });

        // Now make them non-nullable and add unique constraint
        Schema::table('whatsapp_campaigns', function (Blueprint $table) {
            $table->uuid('campaign_code')->unique()->change();
            $table->uuid('api_key')->change();
            $table->index('api_key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_campaigns', function (Blueprint $table) {
            $table->dropIndex(['campaign_code']);
            $table->dropIndex(['api_key']);
            $table->dropColumn(['campaign_code', 'api_key']);
        });
    }
};
