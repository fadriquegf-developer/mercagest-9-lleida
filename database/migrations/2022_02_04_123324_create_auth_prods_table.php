<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Sector;

class CreateAuthProdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_prods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Sector::class)->constrained();
            $table->json('name');
            $table->json('slug')->nullable();
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
        Schema::dropIfExists('auth_prods');
    }
}
