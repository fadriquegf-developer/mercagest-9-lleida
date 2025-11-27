<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Market;
use App\Models\Rate;

class CreateStallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stalls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Market::class)->constrained();
            $table->foreignIdFor(Rate::class)->constrained();
            $table->boolean('active')->default(0);
            $table->string('num');
            $table->integer('length');
            $table->string('image', 500)->nullable();
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
        Schema::dropIfExists('stalls');
    }
}
