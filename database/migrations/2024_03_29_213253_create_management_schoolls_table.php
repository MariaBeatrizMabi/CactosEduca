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
        Schema::create('management_schoolls', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('city', 255);
            $table->string('address');
            $table->string('acess_cod')->unique();
            $table->integer('zip_code');
            $table->enum('type',['admin','admin_seduc','school','teacher']);
            $table->string('password');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('management_schoolls');
    }
};
