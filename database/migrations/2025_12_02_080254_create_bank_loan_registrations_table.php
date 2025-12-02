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
        Schema::create('bank_loan_registrations', function (Blueprint $table) {
            $table->id();
            $table->integer('registration_number');
            $table->integer('year');
            $table->date('date');
            $table->string('name');
            $table->string('birthplace');
            $table->date('birthdate');
            $table->string('occupation');
            $table->text('address');
            $table->foreignId('village_id')->constrained('villages')->onDelete('cascade');
            $table->string('bank');
            $table->string('kohir');
            $table->string('persil')->nullable();
            $table->string('nib')->nullable();
            $table->string('no_shm')->nullable();
            $table->string('land_of_area');
            $table->text('note')->nullable();
            $table->string('status')->default('on_progress');
            $table->foreignId('person_in_charge_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['registration_number', 'year']);
            $table->index('status');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_loan_registrations');
    }
};
