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
        Schema::create('heir_certificates', function (Blueprint $table) {
            $table->id();
            $table->date('certificate_date');
            $table->string('applicant_name');
            $table->text('applicant_address');
            $table->string('deceased_name');
            $table->string('place_of_death');
            $table->date('date_of_death');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Indexes for performance
            $table->index('certificate_date');
            $table->index('date_of_death');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heir_certificates');
    }
};
