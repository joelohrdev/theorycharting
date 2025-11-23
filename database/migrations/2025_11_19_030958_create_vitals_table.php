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
        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('temperature')->nullable();
            $table->string('temperature_source')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->string('heart_rate_source')->nullable();
            $table->integer('resp')->nullable();
            $table->integer('systolic')->nullable();
            $table->integer('diastolic')->nullable();
            $table->string('bp_source')->nullable();
            $table->string('bp_method')->nullable();
            $table->string('patient_position')->nullable();
            $table->string('abdominal_girth')->nullable();
            $table->string('pain_scale')->nullable();
            $table->string('pain_score')->nullable();
            $table->integer('pain_goal')->nullable();
            $table->string('pain_location')->nullable();
            $table->json('pain_descriptors')->nullable();
            $table->integer('sp02')->nullable();
            $table->string('oxygen_device')->nullable();
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
        Schema::dropIfExists('vitals');
    }
};
