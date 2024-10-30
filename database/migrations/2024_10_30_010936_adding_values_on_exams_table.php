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
        Schema::table('exams', function (Blueprint $table) {
            $table->enum('reading', [
                'not_reader',
                'syllable_reader',
                'word_reader',
                'sentence_reader',
                'fluent_text_reader',
                'no_fluent_text_reader',
                'missed',
                'transferred'
            ])
            ->change()
            ->default('not_reader');

            $table->enum('writing', [
                'pre_syllabic',
                'syllabic',
                'alphabetical_syllabic',
                'alphabetical',
                'missed',
                'transferred'
            ])
            ->change()
            ->default('pre_syllabic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams', function (Blueprint $table) {
            $table->enum('reading', [
                'not_reader',
                'syllable_reader',
                'word_reader',
                'sentence_reader',
                'fluent_text_reader',
                'no_fluent_text_reader',
            ])
                ->change()
                ->default('not_reader');

            $table->enum('writing', [
                'pre_syllabic',
                'syllabic',
                'alphabetical_syllabic',
                'alphabetical',
            ])
                ->change()
                ->default('pre_syllabic');
        });

    }
};
