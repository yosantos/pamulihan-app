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
            $table->decimal('ppat_amount', 15, 2)->default(0)->after('adm_certificate');
            $table->enum('status', ['pending', 'paid', 'completed', 'cancelled'])->default('pending')->after('ppat_amount');
            $table->decimal('paid_amount', 15, 2)->default(0)->after('status');
            $table->integer('completion_number')->nullable()->after('paid_amount');
            $table->integer('completion_year')->nullable()->after('completion_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('land_titles', function (Blueprint $table) {
            $table->dropColumn(['ppat_amount', 'status', 'paid_amount', 'completion_number', 'completion_year']);
        });
    }
};
