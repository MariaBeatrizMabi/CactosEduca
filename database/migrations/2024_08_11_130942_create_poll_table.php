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
        Schema::table('poll', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            
            $table->foreign('school_id')->references('id')->on('management_schools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poll', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->foreign('school_id')->references('user_id')->on('management_schools')->onDelete('cascade');
        });
    }
};
