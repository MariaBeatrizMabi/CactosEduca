<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();

            $table->enum('reading', [
                'not_reader',
                'syllable_reader',
                'word_reader',
                'sentence_reader',
                'fluent_text_reader',
                'no_fluent_text_reader'
            ])->default('not_reader');
            $table->enum('writing', [
                'pre_syllabic',
                'syllabic',
                'alphabetical_syllabic',
                'alphabetical'
            ])->default('not_reader');
            $table->boolean('need_action')->default(false);
            $table->enum('action_status', [
                'effective',
                'in_progress',
                'ineffective',
                'interrupted'
            ])->nullable();

            $table->foreignId('student_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
