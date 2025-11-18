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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('gender');
            $table->date('birth_date');
            $table->string('mrn');
            $table->string('room');
            $table->timestamp('admission_date');
            $table->string('attending_md');
            $table->string('diagnosis');
            $table->string('diet_order');
            $table->string('activity_level');
            $table->string('procedure');
            $table->string('status');
            $table->string('isolation');
            $table->string('unit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
