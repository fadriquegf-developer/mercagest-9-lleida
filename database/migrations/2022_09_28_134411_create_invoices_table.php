<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Person;
use App\Models\MarketGroup;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Person::class)->constrained('persons')->onDelete('cascade');
            $table->foreignIdFor(MarketGroup::class)->constrained();
            $table->enum('type', ['mensual', 'trimestral']);
            $table->integer('month')->nullable();
            $table->integer('trimestral')->nullable();
            $table->integer('year');
            $table->float('total');
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
        Schema::dropIfExists('invoices');
    }
};
