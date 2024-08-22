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
        Schema::create('poll', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('school_id')->constrained('management_schools', 'user_id');
            $table->foreignId('class_id')->constrained('class');
            $table->boolean('active')->default(true);
            $table->year('year');
            
            $table->timestamps();
        });
    } 

    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poll');
    }
};
