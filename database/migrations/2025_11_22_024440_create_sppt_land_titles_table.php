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
        Schema::create('sppt_land_titles', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->integer('year');
            $table->string('owner');
            $table->string('block');
            $table->foreignId('village_id')->nullable()->constrained('villages')->onDelete('set null');
            $table->decimal('land_area', 10, 2)->nullable()->default(0);
            $table->decimal('building_area', 10, 2)->nullable()->default(0);
            $table->timestamps();

            $table->index('number');
            $table->index('year');
            $table->index('village_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sppt_land_titles');
    }
};
