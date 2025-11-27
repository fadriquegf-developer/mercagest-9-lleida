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
            $table->string('phone_2', 20)->nullable()->after('phone');
            $table->string('phone_3', 20)->nullable()->after('phone_2');
            $table->text('comment')->nullable()->after('province');
            $table->date('unsubscribe_date')->nullable()->after('comment');
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
            $table->dropColumn('phone_2');
            $table->dropColumn('phone_3');
            $table->dropColumn('comment');
            $table->dropColumn('unsubscribe_date');
        });
    }
};
