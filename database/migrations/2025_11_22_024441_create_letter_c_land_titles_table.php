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
        Schema::create('letter_c_land_titles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('number_of_c');
            $table->string('number_of_persil');
            $table->string('class')->nullable();
            $table->decimal('land_area', 10, 2)->nullable()->default(0);
            $table->date('date')->nullable();
            $table->timestamps();

            $table->index('number_of_c');
            $table->index('number_of_persil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letter_c_land_titles');
    }
};
