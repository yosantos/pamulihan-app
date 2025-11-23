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
        Schema::create('land_title_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_title_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['administration', 'ppat']);
            $table->decimal('amount', 15, 2)->nullable(); // For administration (direct amount)
            $table->decimal('percentage', 5, 2)->nullable(); // For ppat (percentage)
            $table->decimal('calculated_amount', 15, 2)->default(0); // Final calculated amount
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('land_title_recipients');
    }
};
