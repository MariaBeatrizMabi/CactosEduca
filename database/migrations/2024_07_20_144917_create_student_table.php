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
        Schema::create('student', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->integer('age');
            $table->date('enrollment_date');
            $table->longText('comments')->nullable();

            $table->unsignedBigInteger('school_id');
            $table->foreignId('group_id')->constrained('class');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');
    }
};
