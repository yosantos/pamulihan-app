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
            $table->boolean('is_heir')
                ->default(false)
                ->after('land_title_type_id')
                ->comment('Indicates if this land title is for an heir');

            $table->index('is_heir');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('land_titles', function (Blueprint $table) {
            $table->dropIndex(['is_heir']);
            $table->dropColumn('is_heir');
        });
    }
};
