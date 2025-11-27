<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Stall;
use App\Models\Person;

class CreateHistoricalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historicals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Stall::class)->constrained();
            $table->foreignIdFor(Person::class)->constrained('persons');
            $table->date('start_at');
            $table->date('ends_at')->nullable();
            $table->string('reason')->nullable();
            $table->string('explained_reason')->nullable();
            $table->enum('family_transfer', ['family', 'non-family'])->nullable();
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
        Schema::dropIfExists('historicals');
    }
}
