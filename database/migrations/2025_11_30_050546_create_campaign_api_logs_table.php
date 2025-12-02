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
        Schema::create('campaign_api_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('whatsapp_campaigns')->onDelete('cascade');
            $table->string('phone');
            $table->json('request_data');
            $table->string('response_status', 50);
            $table->text('error_message')->nullable();
            $table->string('ip_address', 45);
            $table->timestamps();

            $table->index(['campaign_id', 'created_at']);
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_api_logs');
    }
};
