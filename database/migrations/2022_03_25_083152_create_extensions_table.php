<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Models\Stall;
use \App\Models\Person;

class CreateExtensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extensions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Stall::class)->constrained();
            $table->foreignIdFor(Person::class)->constrained('persons');
            $table->integer('extension');
            $table->text('description')->nullable();
            $table->integer('length');
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
        Schema::dropIfExists('extensions');
    }
}
