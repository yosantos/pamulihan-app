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
        Schema::create('family_id_cards', function (Blueprint $table) {
            $table->id();
            $table->string('no_registration', 50)->unique();
            $table->string('name');
            $table->date('date');
            $table->date('due_date');
            $table->string('national_id_number', 20);
            $table->text('address');
            $table->foreignId('village_id')->constrained('villages')->onDelete('cascade');
            $table->string('phone_number', 20);
            $table->text('note')->nullable();
            $table->string('status')->default('on_progress');
            $table->text('admin_memo')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->foreignId('person_in_charge_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('no_registration');
            $table->index('status');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_id_cards');
    }
};
