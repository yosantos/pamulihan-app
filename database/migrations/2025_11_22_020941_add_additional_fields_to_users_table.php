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
        Schema::table('users', function (Blueprint $table) {
            $table->string('national_id_number', 16)->nullable()->after('phone');
            $table->string('birthplace')->nullable()->after('national_id_number');
            $table->date('birthdate')->nullable()->after('birthplace');
            $table->string('occupation')->nullable()->after('birthdate');
            $table->string('province')->nullable()->after('occupation');
            $table->string('city')->nullable()->after('province');
            $table->string('district')->nullable()->after('city');
            $table->string('village')->nullable()->after('district');
            $table->unsignedTinyInteger('rt')->nullable()->after('village');
            $table->unsignedTinyInteger('rw')->nullable()->after('rt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'national_id_number',
                'birthplace',
                'birthdate',
                'occupation',
                'province',
                'city',
                'district',
                'village',
                'rt',
                'rw',
            ]);
        });
    }
};
