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
            $table->string('type_address', 2)->nullable()->after('dni');
            $table->string('number_address')->nullable()->after('address');
            $table->string('extra_address')->nullable()->after('number_address');
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
            $table->dropColumn('type_address');
            $table->dropColumn('number_address');
            $table->dropColumn('extra_address');
        });
    }
};
