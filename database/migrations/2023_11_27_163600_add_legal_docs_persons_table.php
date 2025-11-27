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
            $table->string('legal_doc1')->nullable();
            $table->integer('legal_doc1_user')->nullable()->unsigned();
            $table->date('legal_doc1_signature_date')->nullable();
            $table->string('legal_doc2')->nullable();
            $table->integer('legal_doc2_user')->nullable()->unsigned();
            $table->date('legal_doc2_signature_date')->nullable();
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
            $table->dropColumn('legal_doc1');
            $table->dropColumn('legal_doc1_user');
            $table->dropColumn('legal_doc1_signature_date');
            $table->dropColumn('legal_doc2');
            $table->dropColumn('legal_doc2_user');
            $table->dropColumn('legal_doc2_signature_date');
        });
    }
};
