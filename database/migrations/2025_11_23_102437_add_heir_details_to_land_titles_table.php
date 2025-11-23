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
        Schema::table('land_titles', function (Blueprint $table) {
            $table->string('heir_from_name')->nullable()->after('is_heir');
            $table->string('death_place')->nullable()->after('heir_from_name');
            $table->date('death_date')->nullable()->after('death_place');
            $table->string('death_certificate_number')->nullable()->after('death_date');
            $table->string('death_certificate_issuer')->nullable()->after('death_certificate_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('land_titles', function (Blueprint $table) {
            $table->dropColumn([
                'heir_from_name',
                'death_place',
                'death_date',
                'death_certificate_number',
                'death_certificate_issuer',
            ]);
        });
    }
};
