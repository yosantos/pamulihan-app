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
        Schema::table('letter_c_land_titles', function (Blueprint $table) {
            $table->foreignId('village_id')
                ->nullable()
                ->after('name')
                ->constrained('villages')
                ->nullOnDelete();

            $table->index('village_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_c_land_titles', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
            $table->dropIndex(['village_id']);
            $table->dropColumn('village_id');
        });
    }
};
