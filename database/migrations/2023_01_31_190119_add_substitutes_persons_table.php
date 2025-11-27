<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->string('substitute1_name')->nullable();
            $table->string('substitute1_dni')->nullable();
            $table->string('substitute1_dni_img')->nullable();
            $table->string('substitute1_img')->nullable();
            $table->string('substitute2_name')->nullable();
            $table->string('substitute2_dni')->nullable();
            $table->string('substitute2_dni_img')->nullable();
            $table->string('substitute2_img')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropColumn('substitute1_name');
            $table->dropColumn('substitute1_dni');
            $table->dropColumn('substitute1_dni_img');
            $table->dropColumn('substitute1_img');
            $table->dropColumn('substitute2_name');
            $table->dropColumn('substitute2_dni');
            $table->dropColumn('substitute2_dni_img');
            $table->dropColumn('substitute2_img');
        });
    }
};
