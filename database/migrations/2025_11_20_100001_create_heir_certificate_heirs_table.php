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
        Schema::create('heir_certificate_heirs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('heir_certificate_id')
                ->constrained('heir_certificates')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('heir_name');
            $table->text('heir_address')->nullable();
            $table->string('relationship')->nullable();
            $table->timestamps();

            // Index for foreign key
            $table->index('heir_certificate_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heir_certificate_heirs');
    }
};
