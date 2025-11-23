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
        Schema::create('land_title_applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_title_id')->constrained('land_titles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('land_title_applicant_type_id')->constrained('land_title_applicant_types')->onDelete('cascade');
            $table->timestamps();

            $table->index('land_title_id');
            $table->index('user_id');
            $table->index('land_title_applicant_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('land_title_applicants');
    }
};
