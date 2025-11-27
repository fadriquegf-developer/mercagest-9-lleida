<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Invoice;
use App\Models\Stall;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_concepts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Invoice::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Stall::class)->constrained();
            $table->enum('concept', ['stall', 'expenses', 'bonuses']); //Definimos si la linea hace refereincia al pago de un stall, es una gasto, o un bonus (lo bonuses restaran al total)
            $table->enum('concept_type', ['fixed', 'meters']);  //Sera una linea de gasto fijo, o dependera de los metros de la parada
            $table->enum('type_rate', ['daily', 'fixed']); //Sera una linea de gasto fijo, o multiplicara por la cantidad de dias
            $table->float('length')->default(0);
            $table->integer('qty_days');
            $table->float('price');
            $table->float('subtotal');
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
        Schema::dropIfExists('invoice_concepts');
    }
};
