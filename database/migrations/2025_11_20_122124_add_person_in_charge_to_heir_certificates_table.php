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
        Schema::table('heir_certificates', function (Blueprint $table) {
            $table->foreignId('person_in_charge_id')
                ->nullable()
                ->after('status')
                ->constrained('users')
                ->onDelete('set null');

            // Add index for performance
            $table->index('person_in_charge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('heir_certificates', function (Blueprint $table) {
            $table->dropForeign(['person_in_charge_id']);
            $table->dropIndex(['person_in_charge_id']);
            $table->dropColumn('person_in_charge_id');
        });
    }
};
