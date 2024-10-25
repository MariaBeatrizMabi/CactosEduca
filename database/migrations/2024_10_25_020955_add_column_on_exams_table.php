<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exams', function(Blueprint $table){

            $table->unsignedBigInteger('poll_id')
                ->after('student_id');

            $table->foreign('poll_id')
                ->references('id')
                ->on('poll');

        });
    }

    public function down(): void
    {
        Schema::table('exams', function(Blueprint $table){
            $table->dropConstrainedForeignId('poll_id');
        });
    }
};
