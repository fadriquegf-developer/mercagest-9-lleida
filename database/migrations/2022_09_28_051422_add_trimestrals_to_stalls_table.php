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
        Schema::table('stalls', function (Blueprint $table) {
            $table->boolean('trimestral_1')->default(1);
            $table->boolean('trimestral_2')->default(1);
            $table->boolean('trimestral_3')->default(1);
            $table->boolean('trimestral_4')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stalls', function (Blueprint $table) {
            $table->dropColumn('trimestral_1');
            $table->dropColumn('trimestral_2');
            $table->dropColumn('trimestral_3');
            $table->dropColumn('trimestral_4');
        });
    }
};
