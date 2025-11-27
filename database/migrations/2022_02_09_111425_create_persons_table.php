<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 500);
            $table->string('email', 100)->nullable();
            $table->string('dni', 20)->unique();
            $table->string('address', 500)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('iban', 100)->nullable();
            $table->string('image', 500)->nullable();
            $table->string('pdf1', 500)->nullable();
            $table->string('pdf2', 500)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('zip', 255)->nullable();
            $table->string('region', 255)->nullable();
            $table->string('province', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
    }
}
