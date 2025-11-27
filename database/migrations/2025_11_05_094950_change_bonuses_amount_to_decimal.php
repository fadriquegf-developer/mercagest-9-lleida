<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBonusesAmountToDecimal extends Migration
{
    public function up()
    {
        Schema::table('bonuses', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });
    }

    public function down()
    {
        Schema::table('bonuses', function (Blueprint $table) {
            $table->integer('amount')->change();
        });
    }
}
