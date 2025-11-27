<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\AuthProd;
use App\Models\Stall;

class CreateConcessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(AuthProd::class)->constrained();
            $table->foreignIdFor(Stall::class)->constrained();
            $table->date('start_at');
            $table->date('end_at');
            $table->string('pdf', 500)->nullable();
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
        Schema::dropIfExists('concessions');
    }
}
