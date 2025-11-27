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
            $table->string('legal_doc3')->nullable();
            $table->integer('legal_doc3_user')->nullable()->unsigned();
            $table->date('legal_doc3_signature_date')->nullable();
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
            $table->dropColumn('legal_doc3');
            $table->dropColumn('legal_doc3_user');
            $table->dropColumn('legal_doc3_signature_date');
        });
    }
};
