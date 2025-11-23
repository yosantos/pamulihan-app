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
        Schema::create('land_titles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_title_type_id')->constrained('land_title_types')->onDelete('cascade');
            $table->foreignId('sppt_land_title_id')->nullable()->constrained('sppt_land_titles')->onDelete('set null');
            $table->foreignId('letter_c_land_title_id')->nullable()->constrained('letter_c_land_titles')->onDelete('set null');
            $table->decimal('transaction_amount', 15, 2)->nullable()->default(0);
            $table->text('transaction_amount_wording')->nullable();
            $table->decimal('area_of_the_land', 10, 2)->nullable()->default(0);
            $table->text('area_of_the_land_wording')->nullable();
            $table->decimal('pph', 15, 2)->nullable()->default(0);
            $table->decimal('bphtb', 15, 2)->nullable()->default(0);
            $table->decimal('adm', 15, 2)->nullable()->default(0);
            $table->decimal('pbb', 15, 2)->nullable()->default(0);
            $table->decimal('adm_certificate', 15, 2)->nullable()->default(0);
            $table->decimal('total_amount', 15, 2)->nullable()->default(0);
            $table->text('north_border')->nullable();
            $table->text('east_border')->nullable();
            $table->text('west_border')->nullable();
            $table->text('south_border')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index('land_title_type_id');
            $table->index('sppt_land_title_id');
            $table->index('letter_c_land_title_id');
            $table->index('created_by');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('land_titles');
    }
};
