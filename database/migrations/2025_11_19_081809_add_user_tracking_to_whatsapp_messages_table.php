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
            $table->foreignId('created_by')
                ->nullable()
                ->after('retry_count')
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreignId('sent_by')
                ->nullable()
                ->after('created_by')
                ->constrained('users')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['sent_by']);
            $table->dropColumn(['created_by', 'sent_by']);
        });
    }
};
