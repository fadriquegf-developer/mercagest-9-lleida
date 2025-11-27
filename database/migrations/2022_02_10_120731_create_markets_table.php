<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Town;
use App\Models\Rate;

class CreateMarketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Town::class)->constrained();
            $table->foreignIdFor(Rate::class)->nullable()->constrained();
            $table->json('name');
            $table->json('slug')->nullable();
            $table->string('market_group')->nullable();
            $table->string('days_of_week', 500)->nullable();
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
        Schema::dropIfExists('markets');
    }
}
