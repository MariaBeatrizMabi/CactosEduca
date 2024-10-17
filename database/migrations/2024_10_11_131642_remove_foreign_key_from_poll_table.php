<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('poll', function (Blueprint $table) {
            // Remover a chave estrangeira
            $table->dropForeign(['school_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('poll', function (Blueprint $table) {
            // Restaurar a chave estrangeira (referenciando user_id, caso necessário)
            $table->foreign('school_id')
                  ->references('user_id')
                  ->on('management_schools')
                  ->onDelete('cascade');
        });
    }
};
