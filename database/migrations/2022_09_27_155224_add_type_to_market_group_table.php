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
        Schema::table('market_groups', function (Blueprint $table) {
            $table->enum('payment', ['mensual', 'trimestral'])->default('mensual')->after('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('market_groups', function (Blueprint $table) {
            $table->dropColumn('payment');
        });
    }
};
