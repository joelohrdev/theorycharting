<?php

declare(strict_types=1);

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
        Schema::create('intakes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('appetite')->nullable();
            $table->text('appetite_comment')->nullable();
            $table->json('unable_to_eat_drink')->nullable();
            $table->text('unable_to_eat_drink_comment')->nullable();
            $table->unsignedBigInteger('percentage_eaten')->nullable();
            $table->unsignedBigInteger('liquids')->nullable();
            $table->unsignedBigInteger('urine')->nullable();
            $table->unsignedBigInteger('unmeasured_urine_occurrence')->nullable();
            $table->json('urine_characteristics')->nullable();
            $table->text('urine_characteristics_comment')->nullable();
            $table->unsignedBigInteger('stool')->nullable();
            $table->json('stool_amount')->nullable();
            $table->text('stool_amount_comment')->nullable();
            $table->unsignedBigInteger('unmeasured_stool_occurrence')->nullable();
            $table->json('stool_characteristics')->nullable();
            $table->unsignedBigInteger('unmeasured_emesis_occurrence')->nullable();
            $table->json('emesis_characteristics')->nullable();
            $table->text('emesis_characteristics_comment')->nullable();
            $table->json('emesis_amount')->nullable();
            $table->text('emesis_amount_comment')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intakes');
    }
};
