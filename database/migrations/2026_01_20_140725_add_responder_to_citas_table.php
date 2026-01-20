<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('citas', function (Blueprint $table) {
            // Aquí agregamos la columna que falta
            $table->foreignId('responder_id')->nullable()->constrained('users');
        });
    }

    public function down()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['responder_id']);
            $table->dropColumn('responder_id');
        });
    }
};