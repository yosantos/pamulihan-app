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
        Schema::create('document_legalizations', function (Blueprint $table) {
            $table->id();
            $table->string('number_legalization', 50)->unique();
            $table->date('date');
            $table->string('type_of_document');
            $table->string('name');
            $table->string('occupation');
            $table->text('address');
            $table->foreignId('village_id')->constrained('villages')->onDelete('cascade');
            $table->text('main_content_of_document');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index('number_legalization');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_legalizations');
    }
};
