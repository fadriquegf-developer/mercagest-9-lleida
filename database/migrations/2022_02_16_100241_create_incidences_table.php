<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Stall;
use App\Models\Calendar;
use App\Models\ContactEmail;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidences', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Stall::class)->constrained();
            $table->foreignIdFor(ContactEmail::class)->nullable()->constrained();
            $table->foreignIdFor(Calendar::class)->nullable()->constrained('calendar');
            $table->string('title');
            $table->string('type');
            $table->tinyInteger('add_absence')->default(false);
            $table->text('description');
            $table->string('image')->nullable();
            $table->enum('status', ['pending', 'solved'])->default('pending');
            $table->date('date_incidence');
            $table->tinyInteger('send')->default(false);
            $table->date('send_at')->nullable();
            $table->date('date_solved')->nullable();
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
        Schema::dropIfExists('incidences');
    }
};
