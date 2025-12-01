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
        Schema::create('adls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('bathing')->nullable();
            $table->text('bathing_comment')->nullable();
            $table->json('bathing_level_of_assist')->nullable();
            $table->text('bathing_level_of_assist_comment')->nullable();
            $table->json('oral_care')->nullable();
            $table->text('oral_care_comment')->nullable();
            $table->json('oral_care_level_of_assist')->nullable();
            $table->text('oral_care_level_of_assist_comment')->nullable();
            $table->json('linen_change')->nullable();
            $table->text('linen_change_comment')->nullable();
            $table->json('hair')->nullable();
            $table->text('hair_comment')->nullable();
            $table->json('shave')->nullable();
            $table->text('shave_comment')->nullable();
            $table->json('deodorant')->nullable();
            $table->text('deodorant_comment')->nullable();
            $table->json('nail_care')->nullable();
            $table->text('nail_care_comment')->nullable();
            $table->json('skin')->nullable();
            $table->text('skin_comment')->nullable();
            $table->text('observations')->nullable();
            $table->json('activity')->nullable();
            $table->text('activity_comment')->nullable();
            $table->json('mobility')->nullable();
            $table->text('mobility_comment')->nullable();
            $table->json('level_of_transfer_assist')->nullable();
            $table->text('level_of_transfer_assist_comment')->nullable();
            $table->json('assistive_device')->nullable();
            $table->text('assistive_device_comment')->nullable();
            $table->unsignedBigInteger('distance_ambulated')->nullable();
            $table->json('ambulation_response')->nullable();
            $table->text('ambulation_response_comment')->nullable();
            $table->json('level_of_repositioning_assistance')->nullable();
            $table->text('level_of_repositioning_assistance_comment')->nullable();
            $table->json('repositioned')->nullable();
            $table->text('repositioned_comment')->nullable();
            $table->json('positioning_frequency')->nullable();
            $table->text('positioning_frequency_comment')->nullable();
            $table->json('head_of_bed_elevated')->nullable();
            $table->text('head_of_bed_elevated_comment')->nullable();
            $table->json('heels_feet')->nullable();
            $table->text('heels_feet_comment')->nullable();
            $table->json('hands_arms')->nullable();
            $table->text('hands_arms_comment')->nullable();
            $table->json('range_of_motion')->nullable();
            $table->text('range_of_motion_comment')->nullable();
            $table->json('transport_method')->nullable();
            $table->text('transport_method_comment')->nullable();
            $table->json('anti_embolism_device')->nullable();
            $table->text('anti_embolism_device_comment')->nullable();
            $table->json('anti_embolism_status')->nullable();
            $table->text('anti_embolism_status_comment')->nullable();
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
        Schema::dropIfExists('adls');
    }
};
