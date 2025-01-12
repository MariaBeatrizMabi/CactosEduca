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
        Schema::create('literacy_parameters_value_exam', function (Blueprint $table) {
            $table->id();
            $table->foreignId('literacy_parameter_value')->constrained();
            $table->foreignId('exams')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('literacy_parameter_value_exam');
    }
};
