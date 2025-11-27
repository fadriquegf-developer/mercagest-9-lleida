<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Person;
use App\Models\Stall;

class CreateAbsencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Person::class)->constrained('persons');
            $table->foreignIdFor(Stall::class)->constrained();
            $table->enum('type', ['justificada', 'no-justificada']);
            $table->text('cause')->nullable();
            $table->string('document', 500)->nullable();
            $table->date('start');
            $table->date('end');
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
        Schema::dropIfExists('absences');
    }
}
