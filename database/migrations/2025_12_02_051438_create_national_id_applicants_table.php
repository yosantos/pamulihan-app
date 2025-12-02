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
        Schema::create('national_id_applicants', function (Blueprint $table) {
            $table->id();
            $table->string('no_register', 50)->unique();
            $table->date('date');
            $table->string('national_id_number', 20);
            $table->string('name');
            $table->text('address');
            $table->foreignId('village_id')->constrained('villages')->onDelete('cascade');
            $table->enum('sex', ['f', 'm']);
            $table->timestamps();

            $table->index('no_register');
            $table->index('national_id_number');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('national_id_applicants');
    }
};
