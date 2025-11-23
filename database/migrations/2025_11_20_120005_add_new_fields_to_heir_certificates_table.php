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
            // Add certificate_number field (auto-incrementing per year)
            $table->unsignedInteger('certificate_number')->nullable()->after('id');

            // Add year field to track the year for certificate numbering
            $table->unsignedInteger('year')->nullable()->after('certificate_number');

            // Add status field with enum values
            $table->enum('status', ['on_progress', 'completed'])
                ->default('on_progress')
                ->after('date_of_death');

            // Add phone_number field (nullable)
            $table->string('phone_number')->nullable()->after('applicant_address');

            // Add indexes for performance
            $table->index('status');
            $table->index('year');

            // Add unique constraint on certificate_number and year combination
            $table->unique(['certificate_number', 'year'], 'unique_certificate_per_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('heir_certificates', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['status']);
            $table->dropIndex(['year']);
            $table->dropUnique('unique_certificate_per_year');

            // Drop columns
            $table->dropColumn([
                'certificate_number',
                'year',
                'status',
                'phone_number',
            ]);
        });
    }
};
