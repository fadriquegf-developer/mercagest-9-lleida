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
        Schema::table('rates', function (Blueprint $table) {
            $table->double('price_expenses', 8, 2)->after('price')->default(0);
            $table->enum('price_type', ['fixed', 'meters'])->after('price');
            $table->enum('price_expenses_type', ['fixed', 'meters'])->after('price_expenses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropColumn('price_expenses');
            $table->dropColumn('price_type');
            $table->dropColumn('price_expenses_type');
        });
    }
};
