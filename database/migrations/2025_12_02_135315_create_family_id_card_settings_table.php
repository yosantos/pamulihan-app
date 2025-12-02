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
        Schema::create('family_id_card_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_campaign_id')->nullable()->constrained('whatsapp_campaigns')->onDelete('set null');
            $table->foreignId('rejection_campaign_id')->nullable()->constrained('whatsapp_campaigns')->onDelete('set null');
            $table->foreignId('completion_campaign_id')->nullable()->constrained('whatsapp_campaigns')->onDelete('set null');
            $table->timestamps();
        });

        // Insert default settings row
        DB::table('family_id_card_settings')->insert([
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_id_card_settings');
    }
};
