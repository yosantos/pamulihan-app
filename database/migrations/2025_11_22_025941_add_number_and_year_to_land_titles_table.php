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
            $table->unsignedInteger('number')->nullable()->after('id');
            $table->unsignedInteger('year')->nullable()->after('number');

            // Add index for better query performance
            $table->index(['year', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('land_titles', function (Blueprint $table) {
            $table->dropIndex(['year', 'number']);
            $table->dropColumn(['number', 'year']);
        });
    }
};
